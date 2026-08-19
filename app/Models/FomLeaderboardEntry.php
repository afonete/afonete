<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Weekly LEADERBOARD (user dashboard) — top 10 earners.
 *
 * "Earned From" = UVP or FOM Licence Miner Packages:
 *   UVP earned  = UVP referral bonus rows (source ≠ fom_referral, not
 *                 reversed/expired) created inside the week window.
 *   FOM earned  = FOM_WEEKLY_REFERRAL_PAYOUT transactions (binary match +
 *                 direct sponsors → cashout) + FOM incentive awards inside
 *                 the window.
 *
 * Entries are keyed by week_start (Monday) — regenerated weekly by the
 * Monday cron and self-healed on first view of a new week. Admin can PIN
 * any user at any position (is_manual=1): pinned rows survive regeneration;
 * system rows fill the remaining positions in earning order, shifting
 * down/around the pins exactly as the admin arranged them.
 */
class FomLeaderboardEntry extends Model
{
    public const MAX_POSITIONS = 10;

    protected $table = 'fom_leaderboard_entries';

    protected $fillable = [
        'week_start', 'position', 'user_id', 'display_name',
        'total_earned', 'earned_from', 'is_manual',
    ];

    protected $casts = [
        'week_start'   => 'date',
        'total_earned' => 'decimal:4',
        'is_manual'    => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Monday of the current leaderboard week. */
    public static function currentWeekStart(): Carbon
    {
        return Carbon::now()->startOfWeek();
    }

    public static function ensureTable(): void
    {
        try {
            if (!Schema::hasTable('fom_leaderboard_entries')) {
                Schema::create('fom_leaderboard_entries', function ($table) {
                    $table->id();
                    $table->date('week_start')->index();
                    $table->unsignedInteger('position');
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->string('display_name')->nullable();
                    $table->decimal('total_earned', 20, 4)->default(0);
                    $table->string('earned_from', 30)->default('UVP');
                    $table->boolean('is_manual')->default(false);
                    $table->timestamps();
                    $table->unique(['week_start', 'position']);
                });
            }
        } catch (\Throwable $e) {
            Log::error('FomLeaderboardEntry ensureTable failed: ' . $e->getMessage());
        }
    }

    /**
     * Earnings for one user inside a window, split by product line.
     * @return array{uvp: float, fom: float, total: float, earned_from: string}
     */
    public static function earningsFor(int $userId, Carbon $from, Carbon $to): array
    {
        $uvp = 0.0;
        $fom = 0.0;

        try {
            $uvp = (float) ReferralBonus::where('user_id', $userId)
                ->where(function ($q) {
                    $q->whereNull('source')->orWhere('source', '!=', 'fom_referral');
                })
                ->whereNotIn('status', ['reversed', 'expired', 'ineligible'])
                ->whereBetween('created_at', [$from, $to])
                ->sum('bonus_amount');
        } catch (\Throwable $e) {
            Log::error("Leaderboard UVP earnings failed for user #{$userId}: " . $e->getMessage());
        }

        try {
            $payouts = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'FOM_WEEKLY_REFERRAL_PAYOUT')
                ->whereBetween('created_at', [$from, $to])
                ->get();
            foreach ($payouts as $t) {
                $d = json_decode((string) $t->transaction_details, true) ?: [];
                $fom += (float) ($d['total_to_cashout'] ?? 0);
            }
        } catch (\Throwable $e) {
            Log::error("Leaderboard FOM payout earnings failed for user #{$userId}: " . $e->getMessage());
        }

        try {
            if (Schema::hasTable('fom_incentive_awards')) {
                $fom += (float) FomIncentiveAward::where('user_id', $userId)
                    ->whereBetween('awarded_at', [$from, $to])
                    ->sum('bonus');
            }
        } catch (\Throwable $e) {
            // incentive table optional — ignore
        }

        return [
            'uvp'         => round($uvp, 4),
            'fom'         => round($fom, 4),
            'total'       => round($uvp + $fom, 4),
            'earned_from' => $fom >= $uvp && $fom > 0 ? 'FOM Licence Miner' : 'UVP',
        ];
    }

    /**
     * (Re)generate the week's leaderboard: keep admin pins exactly where the
     * admin put them; recompute SYSTEM rows from real earnings and fill the
     * free positions from highest to lowest.
     *
     * @return int number of system rows written
     */
    public static function generateForWeek(?Carbon $weekStart = null): int
    {
        self::ensureTable();

        $weekStart = ($weekStart ?: self::currentWeekStart())->copy()->startOfDay();
        $weekEnd   = $weekStart->copy()->addDays(7);

        try {
            // Admin pins survive; system rows are recomputed.
            self::whereDate('week_start', $weekStart->toDateString())->where('is_manual', false)->delete();

            $pins = self::whereDate('week_start', $weekStart->toDateString())->where('is_manual', true)->get();
            $pinnedPositions = $pins->pluck('position')->all();
            $pinnedUserIds   = $pins->pluck('user_id')->filter()->all();

            // Candidate earners: anyone with bonus rows / payouts in window.
            $candidateIds = collect();
            $candidateIds = $candidateIds->merge(
                ReferralBonus::whereBetween('created_at', [$weekStart, $weekEnd])
                    ->whereNotIn('status', ['reversed', 'expired', 'ineligible'])
                    ->pluck('user_id')
            );
            $candidateIds = $candidateIds->merge(
                Transaction::where('transaction_type', 'FOM_WEEKLY_REFERRAL_PAYOUT')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->pluck('user_id')
            );
            try {
                if (Schema::hasTable('fom_incentive_awards')) {
                    $candidateIds = $candidateIds->merge(
                        FomIncentiveAward::whereBetween('awarded_at', [$weekStart, $weekEnd])->pluck('user_id')
                    );
                }
            } catch (\Throwable $e) {
                // optional
            }

            $ranked = [];
            foreach ($candidateIds->unique()->values() as $uid) {
                if (in_array($uid, $pinnedUserIds)) {
                    continue; // pinned users are already on the board
                }
                $e = self::earningsFor((int) $uid, $weekStart, $weekEnd);
                if ($e['total'] <= 0) {
                    continue;
                }
                $ranked[] = ['user_id' => (int) $uid] + $e;
            }
            usort($ranked, fn ($a, $b) => $b['total'] <=> $a['total']);

            // Fill free positions 1..10 top-down around the pins.
            $written = 0;
            $i = 0;
            for ($pos = 1; $pos <= self::MAX_POSITIONS && $i < count($ranked); $pos++) {
                if (in_array($pos, $pinnedPositions)) {
                    continue;
                }
                $row  = $ranked[$i++];
                $user = User::find($row['user_id']);
                self::create([
                    'week_start'   => $weekStart->toDateString(),
                    'position'     => $pos,
                    'user_id'      => $row['user_id'],
                    'display_name' => $user ? ($user->user ?? $user->name) : ('user#' . $row['user_id']),
                    'total_earned' => $row['total'],
                    'earned_from'  => $row['earned_from'],
                    'is_manual'    => false,
                ]);
                $written++;
            }

            return $written;
        } catch (\Throwable $e) {
            Log::error('FomLeaderboardEntry generateForWeek failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * The board for a week (self-heals: generates system rows on the first
     * view of a new week). Ordered by position, max 10 rows.
     */
    public static function board(?Carbon $weekStart = null)
    {
        self::ensureTable();
        $weekStart = ($weekStart ?: self::currentWeekStart())->copy()->startOfDay();

        try {
            if (!self::whereDate('week_start', $weekStart->toDateString())->exists()) {
                self::generateForWeek($weekStart);
            }

            return self::whereDate('week_start', $weekStart->toDateString())
                ->orderBy('position')
                ->limit(self::MAX_POSITIONS)
                ->get();
        } catch (\Throwable $e) {
            Log::error('FomLeaderboardEntry board failed: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * ADMIN: pin a user at a position (1..10). The pinned row takes that
     * exact slot; system rows are re-flowed around all pins, so "system #1
     * becomes second or third" exactly as the admin arranges.
     */
    public static function pin(int $userId, int $position, ?Carbon $weekStart = null, ?float $overrideAmount = null, ?string $overrideSource = null): ?self
    {
        self::ensureTable();
        $weekStart = ($weekStart ?: self::currentWeekStart())->copy()->startOfDay();
        $position  = max(1, min(self::MAX_POSITIONS, $position));

        try {
            $user = User::find($userId);
            if (!$user) {
                return null;
            }

            $weekEnd = $weekStart->copy()->addDays(7);
            $e = self::earningsFor($userId, $weekStart, $weekEnd);

            // One pin per user per week; one pin per position per week.
            self::whereDate('week_start', $weekStart->toDateString())->where('is_manual', true)->where('user_id', $userId)->delete();
            self::whereDate('week_start', $weekStart->toDateString())->where('position', $position)->delete();

            $row = self::create([
                'week_start'   => $weekStart->toDateString(),
                'position'     => $position,
                'user_id'      => $userId,
                'display_name' => $user->user ?? $user->name,
                'total_earned' => $overrideAmount !== null ? round($overrideAmount, 4) : $e['total'],
                'earned_from'  => $overrideSource ?: $e['earned_from'],
                'is_manual'    => true,
            ]);

            // Re-flow system rows around the pins.
            self::generateForWeek($weekStart);

            return $row;
        } catch (\Throwable $e) {
            Log::error('FomLeaderboardEntry pin failed: ' . $e->getMessage());
            return null;
        }
    }

    /** ADMIN: remove a pinned row and re-flow the system entries. */
    public static function unpin(int $entryId): bool
    {
        try {
            $row = self::find($entryId);
            if (!$row || !$row->is_manual) {
                return false;
            }
            $week = Carbon::parse($row->week_start);
            $row->delete();
            self::generateForWeek($week);
            return true;
        } catch (\Throwable $e) {
            Log::error('FomLeaderboardEntry unpin failed: ' . $e->getMessage());
            return false;
        }
    }
}
