<?php

namespace App\Services;

use App\Models\FcStreamlineRank;
use App\Models\Payment;
use App\Models\ChartAccount;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * FcStreamlineRankService — drives the FC VIP Streamline Rank lifecycle.
 *
 * Called by an hourly/daily cron (or opportunistically after each FC payment
 * confirmation) to evaluate every FC user's progress and advance rank states:
 *
 *   locked → active       (auto when RANK ACTIVATION threshold is first met)
 *   active → pending_admin(auto when OWN FC (direct) + TEAM CLUB (downline FC)
 *                          targets are both reached BEFORE deadline)
 *   active → expired      (when deadline passes without both targets met;
 *                          permanent forfeiture for THIS rank)
 *
 * Admin must explicitly call approveRank() to move pending_admin → completed
 * which credits $reward to COMMISSION (cashout Monday) and tokens to
 * AVAILABLE_TOKEN.
 */
class FcStreamlineRankService
{
    /**
     * Evaluate rank progress for one user (or all users when $user is null).
     * Safe to call repeatedly (idempotent). Called from:
     *   - cron:   fc-ranks:evaluate (hourly)
     *   - event:  after every FC payment confirmation (best-effort, wrapped in try/catch)
     *
     * Returns summary counts [activated, pending, expired].
     */
    public static function evaluate(?User $user = null): array
    {
        $stats = ['activated' => 0, 'pending' => 0, 'expired' => 0];

        $users = $user ? collect([$user]) : self::fcActiveUsers();

        foreach ($users as $u) {
            $res = self::evaluateForUser($u);
            foreach ($res as $k => $v) {
                $stats[$k] = ($stats[$k] ?? 0) + $v;
            }
        }

        return $stats;
    }

    /**
     * Fetch users who have ever bought an FC VIP package (status=1), plus
     * users who have an in-progress streamline rank row.
     */
    protected static function fcActiveUsers(): \Illuminate\Support\Collection
    {
        $fcBuyerIds = Payment::where('status', 1)
            ->where(function ($q) {
                $q->where('category', 'FC')
                  ->orWhere('payable_type', \App\Models\FCpackage::class);
            })
            ->pluck('user')
            ->unique()
            ->map(fn ($id) => (int) $id)
            ->all();

        $inProgressIds = FcStreamlineRank::whereIn('status', [
            FcStreamlineRank::STATUS_ACTIVE,
            FcStreamlineRank::STATUS_PENDING_ADMIN,
        ])->pluck('user_id')->unique()->all();

        $ids = array_values(array_unique(array_merge($fcBuyerIds, $inProgressIds)));
        if (empty($ids)) return collect();

        return User::whereIn('id', $ids)->get();
    }

    /**
     * Evaluate ranks for one user in sequential order (Silver→Gold→Diamond→Ambassador).
     */
    public static function evaluateForUser(User $user): array
    {
        $stats = ['activated' => 0, 'pending' => 0, 'expired' => 0];

        $directFcCount   = self::countDirectFcReferrals($user->id);
        $pinCounts       = self::countDownlinePins($user->id);   // ['silver'=>n, 'gold'=>n, ...]
        $teamFcCount     = self::countTeamClubFc($user->id);     // all FC-paid in downline, any depth

        $highestCompleted = FcStreamlineRank::highestCompletedPin($user->id);
        // Next rank to challenge is one past highest completed.
        $nextRankLevel = $highestCompleted + 1;

        // If there's an active challenge, that takes priority (it might expire or complete).
        $active = FcStreamlineRank::currentActiveForUser($user->id);

        // 1) Expiry check on currently-active challenge.
        if ($active && $active->status === FcStreamlineRank::STATUS_ACTIVE) {
            $def = $active->rankDefinition();
            $deadline = $active->deadline_at;

            // Check completion BEFORE checking expiry (user might reach targets on day 65).
            $directNow = $directFcCount;
            $teamNow   = $teamFcCount;
            $ownMet    = $directNow >= (int) ($def['own_fc_direct'] ?? 0);
            $clubMet   = $teamNow   >= (int) ($def['team_club'] ?? 0);

            if ($ownMet && $clubMet) {
                self::markPendingAdmin($active, $directNow, $teamNow, $pinCounts[$def['pin']] ?? 0);
                $stats['pending']++;
                // Fall through to see if activation happens for next rank (will not, since pending now).
            } elseif ($deadline && Carbon::now()->gte($deadline)) {
                self::markExpired($active);
                $stats['expired']++;
                // User cannot proceed further (sequential ranks).
                return $stats;
            } else {
                // Challenge still in progress; nothing to do.
                return $stats;
            }
        }

        // 2) If there is no active, no pending_admin challenge, and next rank is
        //    within range, check activation.
        $hasPending = FcStreamlineRank::where('user_id', $user->id)
            ->where('status', FcStreamlineRank::STATUS_PENDING_ADMIN)
            ->exists();
        if ($hasPending) {
            return $stats; // awaiting admin verification before next rank can activate
        }

        if ($active) {
            return $stats; // something else is in-flight
        }

        if ($nextRankLevel < 1 || $nextRankLevel > 4) {
            return $stats; // already finished Ambassador, or none yet
        }

        // Sequential: user must HAVE completed the prior rank's pin before
        // challenging the next. For rank 1 there is no prerequisite pin.
        // Also: if any prior rank has STATUS_EXPIRED, they're locked out forever.
        $priorExpired = FcStreamlineRank::where('user_id', $user->id)
            ->whereIn('status', [FcStreamlineRank::STATUS_EXPIRED])
            ->where('rank_level', '<', $nextRankLevel)
            ->exists();
        if ($priorExpired) {
            return $stats; // locked out due to expired lower rank
        }

        if ($nextRankLevel > 1 && $highestCompleted < ($nextRankLevel - 1)) {
            return $stats; // must complete prior rank first
        }

        $def = FcStreamlineRank::RANKS[$nextRankLevel];

        // Activation criteria:
        //  • user has at least activation_direct_fc DIRECT FC referrals
        //  • user has at least activation_pins of pin type activation_pin_type
        //    (anywhere in downline, counted across completed ranks)
        $baseMet = $directFcCount >= (int) $def['activation_direct_fc'];
        $pinsMet = true;
        if ((int) $def['activation_pins'] > 0 && $def['activation_pin_type']) {
            $have = (int) ($pinCounts[$def['activation_pin_type']] ?? 0);
            $pinsMet = $have >= (int) $def['activation_pins'];
        }

        if ($baseMet && $pinsMet) {
            self::activateRank($user, $nextRankLevel);
            $stats['activated']++;
        }

        return $stats;
    }

    /* ──────────────────────────────────────────────────────────
     *  Counting helpers
     * ────────────────────────────────────────────────────────── */

    /** Count of DIRECT referrals (L1) who have purchased FC VIP (status=1). */
    public static function countDirectFcReferrals(int $userId): int
    {
        $directIds = User::where('referee_id', $userId)->pluck('id');
        if ($directIds->isEmpty()) return 0;

        return (int) Payment::whereIn('user', $directIds)
            ->where('status', 1)
            ->where(function ($q) {
                $q->where('category', 'FC')
                  ->orWhere('payable_type', \App\Models\FCpackage::class);
            })
            ->distinct('user')
            ->count('user');
    }

    /** Count of all downline users (any depth) who have an FC VIP payment. */
    public static function countTeamClubFc(int $rootUserId): int
    {
        $all = self::allDownlineIds($rootUserId);
        if (empty($all)) return 0;

        return (int) Payment::whereIn('user', $all)
            ->where('status', 1)
            ->where(function ($q) {
                $q->where('category', 'FC')
                  ->orWhere('payable_type', \App\Models\FCpackage::class);
            })
            ->distinct('user')
            ->count('user');
    }

    /** Count how many downline users (any depth) hold each completed pin. */
    public static function countDownlinePins(int $rootUserId): array
    {
        $counts = ['silver' => 0, 'gold' => 0, 'diamond' => 0, 'ambassador' => 0];
        $all = self::allDownlineIds($rootUserId);
        if (empty($all)) return $counts;

        $rows = FcStreamlineRank::whereIn('user_id', $all)
            ->where('status', FcStreamlineRank::STATUS_COMPLETED)
            ->selectRaw('rank_pin, COUNT(*) AS cnt')
            ->groupBy('rank_pin')
            ->pluck('cnt', 'rank_pin');

        foreach ($rows as $pin => $c) {
            $counts[(string) $pin] = (int) $c;
        }
        return $counts;
    }

    /** Collect ALL downline user ids (any depth) via BFS, cycle-safe. */
    protected static function allDownlineIds(int $rootUserId): array
    {
        $visited = [$rootUserId => true];
        $queue = [$rootUserId];
        $out = [];

        while (!empty($queue)) {
            $current = array_shift($queue);
            $directs = User::where('referee_id', $current)->pluck('id');
            foreach ($directs as $did) {
                $did = (int) $did;
                if ($did && !isset($visited[$did])) {
                    $visited[$did] = true;
                    $out[] = $did;
                    $queue[] = $did;
                }
            }
        }
        return $out;
    }

    /* ──────────────────────────────────────────────────────────
     *  State transitions
     * ────────────────────────────────────────────────────────── */

    protected static function activateRank(User $user, int $rankLevel): void
    {
        $row = FcStreamlineRank::forUser($user->id, $rankLevel);
        if ($row->status !== FcStreamlineRank::STATUS_LOCKED) {
            return;
        }

        $start = Carbon::now();
        $row->status       = FcStreamlineRank::STATUS_ACTIVE;
        $row->activated_at = $start;
        $row->deadline_at  = $start->copy()->addDays(FcStreamlineRank::PERIOD_DAYS);
        $row->save();

        Log::info("FC Streamline rank {$rankLevel} (" . FcStreamlineRank::RANKS[$rankLevel]['pin'] . ") activated for user {$user->id}; deadline {$row->deadline_at->toDateString()}.");
    }

    protected static function markPendingAdmin(FcStreamlineRank $row, int $directNow, int $teamNow, int $requiredPinsNow): void
    {
        if ($row->status !== FcStreamlineRank::STATUS_ACTIVE) return;

        $row->status                      = FcStreamlineRank::STATUS_PENDING_ADMIN;
        $row->completed_at                = Carbon::now();
        $row->direct_fc_at_completion     = $directNow;
        $row->team_fc_at_completion       = $teamNow;
        $row->required_pins_at_completion = $requiredPinsNow;
        $row->save();

        Log::info("FC Streamline rank {$row->rank_level} pending admin verification for user {$row->user_id} (direct={$directNow}, team={$teamNow}).");
    }

    protected static function markExpired(FcStreamlineRank $row): void
    {
        if ($row->status !== FcStreamlineRank::STATUS_ACTIVE) return;

        $row->status     = FcStreamlineRank::STATUS_EXPIRED;
        $row->expired_at = Carbon::now();
        $row->save();

        Log::info("FC Streamline rank {$row->rank_level} EXPIRED for user {$row->user_id} (did not meet targets within 65 days).");
    }

    /**
     * Admin approves a pending_admin rank — credits $ to COMMISSION and
     * tokens to AVAILABLE_TOKEN, marks completed.
     */
    public static function approveRank(FcStreamlineRank $row, int $adminId, ?string $notes = null): bool
    {
        if ($row->status !== FcStreamlineRank::STATUS_PENDING_ADMIN) {
            return false;
        }

        $def = $row->rankDefinition();
        if (empty($def)) return false;

        return DB::transaction(function () use ($row, $def, $adminId, $notes) {
            $user = User::find($row->user_id);
            if (!$user) return false;

            $usd    = (float) $def['reward_usd'];
            $tokens = (int)   $def['reward_tokens'];

            // Credit USD → COMMISSION (goes to cashout Monday pipeline).
            $existingComm = (float) ChartAccount::where('user_id', $user->id)
                ->where('acc_type', 'COMMISSION')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'COMMISSION'],
                ['amount'  => $existingComm + $usd]
            );

            // Credit tokens → AVAILABLE_TOKEN.
            $existingAvail = (float) ChartAccount::where('user_id', $user->id)
                ->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                ['amount'  => $existingAvail + $tokens]
            );

            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => Transaction::generateTransactionNo(),
                'transaction_type'    => 'FC_RANK_REWARD',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'rank_level'   => $row->rank_level,
                    'rank_pin'     => $def['pin'],
                    'rank_title'   => $def['title'],
                    'reward_usd'   => $usd,
                    'reward_tokens'=> $tokens,
                    'direct_fc'    => $row->direct_fc_at_completion,
                    'team_fc'      => $row->team_fc_at_completion,
                    'description'  => "FC Streamline Rank reward: {$def['title']} ({$def['pin']})",
                    'status'       => 'success',
                    'username'     => $user->name,
                    'verified_by'  => $adminId,
                ]),
            ]);

            $row->status        = FcStreamlineRank::STATUS_COMPLETED;
            $row->verified_by   = $adminId;
            $row->verified_at   = Carbon::now();
            $row->admin_notes   = $notes;
            $row->reward_usd    = $usd;
            $row->reward_tokens = $tokens;
            $row->save();

            Log::info("FC Streamline rank {$row->rank_level} APPROVED for user {$user->id} by admin {$adminId}: \${$usd}, {$tokens} tokens.");
            return true;
        });
    }

    /** Admin rejects a pending claim (user didn't actually meet targets).
     *  Returns rank to active state so user can keep trying until deadline. */
    public static function rejectRank(FcStreamlineRank $row, int $adminId, ?string $notes = null): bool
    {
        if ($row->status !== FcStreamlineRank::STATUS_PENDING_ADMIN) {
            return false;
        }

        $row->status       = FcStreamlineRank::STATUS_ACTIVE;
        $row->completed_at = null;
        $row->admin_notes  = ($notes ? $notes . ' | ' : '') . 'Rejected by admin #' . $adminId . ' at ' . Carbon::now()->toDateTimeString() . '; returned to active challenge.';
        $row->save();

        Log::info("FC Streamline rank {$row->rank_level} REJECTED for user {$row->user_id} by admin {$adminId}.");
        return true;
    }
}
