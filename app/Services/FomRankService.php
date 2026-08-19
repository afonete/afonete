<?php

namespace App\Services;

use App\Models\User;
use App\Models\Teams;
use App\Models\Payment;
use App\Models\ReferralBonus;
use App\Models\ChartAccount;
use App\Models\FomRank;
use App\Models\FomUserRank;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * FOM Licence Miner RANK engine.
 *
 * Metrics (FOM-only, per user decisions §63/§64):
 *   VB                = WEAKER-SIDE all-time volume bonus
 *                       (min(left, right) of sideVolumeBonusTotals — the
 *                       condition is met when the weak side reaches the
 *                       rank's VB figure).
 *   Personal Turnover = FOM Licence purchases of DIRECT referrals ($).
 *   Team Turnover     = FOM Licence purchases of DIRECT + INDIRECT
 *                       referrals — the whole referral network ($).
 *   Bonus             = FOM referral bonus actually earned (accrued/paid).
 *   Licence           = highest FOM licence the user holds ≥ rank minimum.
 *
 * 90-DAY RULE (§64): the doubling applies to the SAME rank the user was
 *   supposed to achieve — fail a 90-day window (from first FOM activation)
 *   and that next rank's VB requirement doubles to pass it and get the
 *   reward (×2 per missed window).
 *
 * Flow: detectFor() creates PENDING FomUserRank rows → admin approves
 * (reward → CASHOUT) or rejects.
 */
class FomRankService
{
    // ── Metrics ─────────────────────────────────────────────────────────────

    /**
     * Rank VB metric = WEAKER-SIDE all-time Volume Bonus (user spec: "in
     * case weak side total volume bonus is equal to this volume bonus then
     * the condition will be met"). All-time totals from the immutable
     * accrual rows — NOT the wallets Monday matching consumes.
     */
    public static function weakerSideVb(int $userId): float
    {
        $t = FomReferralService::sideVolumeBonusTotals($userId);
        return (float) min((float) $t['left'], (float) $t['right']);
    }

    /** @deprecated kept for compatibility — now weaker-side semantics. */
    public static function lifetimeVb(int $userId): float
    {
        return self::weakerSideVb($userId);
    }

    /**
     * Personal Turnover = total FOM Licence purchases of the user's
     * DIRECT referrals (user spec) — not the user's own purchases.
     */
    public static function personalTurnover(int $userId): float
    {
        try {
            $directIds = User::where('referee_id', $userId)->pluck('id')->all();
            if (empty($directIds)) {
                return 0.0;
            }

            $total = 0.0;
            foreach (array_chunk($directIds, 500) as $chunk) {
                $total += (float) Payment::whereIn('user', array_map('strval', $chunk))
                    ->onlyFom()
                    ->where('status', '1')
                    ->get()
                    ->sum(fn ($p) => (float) ($p->paid ?? $p->amount ?? 0));
            }
            return $total;
        } catch (\Throwable $e) {
            Log::error("FomRankService personalTurnover failed for #{$userId}: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * All DIRECT + INDIRECT referral ids (referee_id chain, any depth,
     * cycle-safe) — Team Turnover counts the whole referral network.
     */
    public static function downlineIds(int $userId): array
    {
        $visited = [$userId => true];
        $queue = [$userId];
        $ids = [];

        try {
            while (!empty($queue)) {
                $current = array_shift($queue);
                foreach (User::where('referee_id', $current)->pluck('id') as $mid) {
                    $mid = (int) $mid;
                    if ($mid && !isset($visited[$mid])) {
                        $visited[$mid] = true;
                        $queue[] = $mid;
                        $ids[] = $mid;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error("FomRankService downlineIds failed for #{$userId}: " . $e->getMessage());
        }

        return $ids;
    }

    public static function teamTurnover(int $userId): float
    {
        $ids = self::downlineIds($userId);
        if (empty($ids)) {
            return 0.0;
        }

        try {
            $total = 0.0;
            foreach (array_chunk($ids, 500) as $chunk) {
                $total += (float) Payment::whereIn('user', array_map('strval', $chunk))
                    ->onlyFom()
                    ->where('status', '1')
                    ->get()
                    ->sum(fn ($p) => (float) ($p->paid ?? $p->amount ?? 0));
            }
            return $total;
        } catch (\Throwable $e) {
            Log::error("FomRankService teamTurnover failed for #{$userId}: " . $e->getMessage());
            return 0.0;
        }
    }

    /** FOM referral bonus the user actually EARNED (accrued or paid). */
    public static function fomBonusEarned(int $userId): float
    {
        try {
            return (float) ReferralBonus::where('user_id', $userId)
                ->where('source', 'fom_referral')
                ->whereIn('status', ['accrued', 'paid'])
                ->sum('bonus_amount');
        } catch (\Throwable $e) {
            return 0.0;
        }
    }

    /** Highest FOM licence rank number the user holds (0 = none). */
    public static function highestLicenceRank(int $userId): int
    {
        try {
            $best = 0;
            foreach (Payment::where('user', (string) $userId)->onlyFom()->where('status', '1')->get() as $p) {
                $name = strtoupper(trim((string) ($p->category ?: $p->package)));
                $best = max($best, FomRank::LICENCE_ORDER[$name] ?? 0);
            }
            return $best;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** Direct referrals with an active (non-expired) FOM payment. */
    public static function activeDirects(int $userId): int
    {
        try {
            $count = 0;
            foreach (User::where('referee_id', $userId)->pluck('id') as $did) {
                $has = Payment::where('user', (string) $did)
                    ->onlyFom()
                    ->where('status', '1')
                    ->where('is_expired', false)
                    ->exists();
                if ($has) {
                    $count++;
                }
            }
            return $count;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    // ── Rank-holder criteria (2 Builders both sides, 3 Juniors, …) ──────────

    /**
     * Map every downline holder of $rankName to the DIRECT branch (line) of
     * $userId they sit under + the side of that branch.
     * @return array<int, array{line: int, side: string}>
     */
    public static function holderLines(int $userId, string $rankName): array
    {
        $holders = FomUserRank::holdersOf($rankName)->flip();
        if ($holders->isEmpty()) {
            return [];
        }

        $result = [];

        try {
            foreach (Teams::where('user_id', $userId)->get() as $direct) {
                $lineRoot = (int) $direct->team_user_id;
                $side = strtoupper(trim((string) $direct->side)) === 'RIGHT' ? 'RIGHT' : 'LEFT';

                // Walk this branch
                $visited = [$userId => true, $lineRoot => true];
                $queue = [$lineRoot];
                while (!empty($queue)) {
                    $cur = array_shift($queue);
                    if ($holders->has($cur) && !isset($result[$cur])) {
                        $result[$cur] = ['line' => $lineRoot, 'side' => $side];
                    }
                    foreach (Teams::where('user_id', $cur)->get() as $t) {
                        $mid = (int) $t->team_user_id;
                        if ($mid && !isset($visited[$mid])) {
                            $visited[$mid] = true;
                            $queue[] = $mid;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error("FomRankService holderLines failed for #{$userId}/{$rankName}: " . $e->getMessage());
        }

        return $result;
    }

    /**
     * Check one {"rank","count","distinct_lines","both_sides"} requirement.
     */
    public static function meetsRankRequirement(int $userId, array $req): bool
    {
        $holders = self::holderLines($userId, (string) ($req['rank'] ?? ''));
        $count = (int) ($req['count'] ?? 1);

        if (count($holders) < $count) {
            return false;
        }

        if (!empty($req['distinct_lines'])) {
            $lines = collect($holders)->pluck('line')->unique();
            if ($lines->count() < $count) {
                return false;
            }
        }

        if (!empty($req['both_sides'])) {
            $sides = collect($holders)->pluck('side')->unique();
            if (!$sides->contains('LEFT') || !$sides->contains('RIGHT')) {
                return false;
            }
        }

        return true;
    }

    // ── 90-DAY RULE ──────────────────────────────────────────────────────────

    /** First FOM activation date (payment created_at) or null. */
    public static function firstFomActivationAt(int $userId): ?Carbon
    {
        try {
            $p = Payment::where('user', (string) $userId)
                ->onlyFom()
                ->where('status', '1')
                ->orderBy('created_at')
                ->first();
            return $p ? Carbon::parse($p->created_at) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Completed 90-day windows since first FOM activation in which the user
     * did NOT earn a NEW rank. Each missed window doubles the next rank's VB
     * requirement (×2 per window).
     *
     * @return array{windows: int, missed: int, multiplier: float, anchor: ?string}
     */
    public static function ninetyDayStatus(int $userId): array
    {
        $anchor = self::firstFomActivationAt($userId);
        if (!$anchor) {
            return ['windows' => 0, 'missed' => 0, 'multiplier' => 1.0, 'anchor' => null];
        }

        $windows = (int) floor($anchor->diffInDays(Carbon::now()) / 90);
        if ($windows <= 0) {
            return ['windows' => 0, 'missed' => 0, 'multiplier' => 1.0, 'anchor' => $anchor->toDateTimeString()];
        }

        $missed = 0;
        try {
            for ($w = 1; $w <= $windows; $w++) {
                $windowStart = $anchor->copy()->addDays(90 * ($w - 1));
                $windowEnd   = $anchor->copy()->addDays(90 * $w);
                $earnedInWindow = FomUserRank::where('user_id', $userId)
                    ->where('status', 'approved')
                    ->whereBetween('detected_at', [$windowStart, $windowEnd])
                    ->exists();
                if (!$earnedInWindow) {
                    $missed++;
                }
            }
        } catch (\Throwable $e) {
            Log::error("FomRankService ninetyDayStatus failed for #{$userId}: " . $e->getMessage());
        }

        return [
            'windows'    => $windows,
            'missed'     => $missed,
            'multiplier' => (float) pow(2, $missed),
            'anchor'     => $anchor->toDateTimeString(),
        ];
    }

    /** Effective VB requirement for a rank for THIS user (90-day rule applied). */
    public static function effectiveVbRequired(int $userId, FomRank $rank): float
    {
        $status = self::ninetyDayStatus($userId);
        return round((float) $rank->vb_required * $status['multiplier'], 4);
    }

    // ── Qualification & detection ────────────────────────────────────────────

    /**
     * Full qualification check for one user × one rank.
     * @return array{qualified: bool, checks: array<string, array{need: mixed, have: mixed, pass: bool}>}
     */
    public static function qualification(User $user, FomRank $rank): array
    {
        $checks = [];

        $effVb = self::effectiveVbRequired($user->id, $rank);
        $vb = self::weakerSideVb($user->id);
        $checks['volume_bonus'] = ['need' => $effVb, 'have' => $vb, 'pass' => $vb >= $effVb];

        $tt = self::teamTurnover($user->id);
        $checks['team_turnover'] = ['need' => (float) $rank->team_turnover, 'have' => $tt, 'pass' => $tt >= (float) $rank->team_turnover];

        $pt = self::personalTurnover($user->id);
        $checks['personal_turnover'] = ['need' => (float) $rank->personal_turnover, 'have' => $pt, 'pass' => $pt >= (float) $rank->personal_turnover];

        $needLic = FomRank::LICENCE_ORDER[strtoupper(trim((string) $rank->licence_min))] ?? 1;
        $hasLic = self::highestLicenceRank($user->id);
        $checks['licence'] = ['need' => $rank->licence_min, 'have' => $hasLic, 'pass' => $hasLic >= $needLic];

        $crit = $rank->criteria_json ?: [];
        $type = $crit['type'] ?? 'none';
        if ($type === 'bonus') {
            $need = (float) ($crit['amount'] ?? 0);
            $have = self::fomBonusEarned($user->id);
            $checks['criteria'] = ['need' => $need, 'have' => $have, 'pass' => $have >= $need];
        } elseif ($type === 'active_directs') {
            $need = (int) ($crit['count'] ?? 0);
            $have = self::activeDirects($user->id);
            $checks['criteria'] = ['need' => $need, 'have' => $have, 'pass' => $have >= $need];
        } elseif ($type === 'ranks') {
            $pass = true;
            foreach ((array) ($crit['requires'] ?? []) as $req) {
                if (!self::meetsRankRequirement($user->id, $req)) {
                    $pass = false;
                    break;
                }
            }
            $checks['criteria'] = ['need' => $rank->criteria_label, 'have' => $pass ? 'met' : 'not met', 'pass' => $pass];
        } else {
            $checks['criteria'] = ['need' => 'none', 'have' => 'n/a', 'pass' => true];
        }

        $qualified = collect($checks)->every(fn ($c) => $c['pass']);

        return ['qualified' => $qualified, 'checks' => $checks];
    }

    /**
     * Detect newly-qualified ranks for one user: creates PENDING rows
     * (sequential — only the next rank above the highest approved/pending).
     * @return int rows created
     */
    public static function detectFor(User $user): int
    {
        FomRank::ensureTableAndData();
        FomUserRank::ensureTable();

        $created = 0;

        try {
            $topLevel = (int) FomUserRank::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'pending'])
                ->max('rank_level');

            foreach (FomRank::ordered()->where('level', '>', $topLevel)->get() as $rank) {
                $q = self::qualification($user, $rank);
                if (!$q['qualified']) {
                    break; // ranks are sequential — stop at the first failure
                }

                FomUserRank::create([
                    'user_id'           => $user->id,
                    'fom_rank_id'       => $rank->id,
                    'rank_name'         => $rank->name,
                    'rank_level'        => $rank->level,
                    'status'            => 'pending',
                    'detected_at'       => Carbon::now(),
                    'criteria_snapshot' => $q['checks'],
                ]);
                $created++;
            }
        } catch (\Throwable $e) {
            Log::error("FomRankService detectFor failed for #{$user->id}: " . $e->getMessage());
        }

        return $created;
    }

    /** Sweep every user with FOM payments (weekly cron). @return int rows created */
    public static function detectAll(): int
    {
        FomRank::ensureTableAndData();
        FomUserRank::ensureTable();

        $created = 0;
        try {
            $userIds = Payment::query()->onlyFom()->where('status', '1')->pluck('user')->unique();
            foreach ($userIds as $uid) {
                $user = User::find((int) $uid);
                if ($user) {
                    $created += self::detectFor($user);
                }
            }
        } catch (\Throwable $e) {
            Log::error('FomRankService detectAll failed: ' . $e->getMessage());
        }

        return $created;
    }

    // ── Admin approve / reject ───────────────────────────────────────────────

    /** Approve a pending rank: reward → CASHOUT atomically. */
    public static function approve(int $userRankId, int $adminId, ?string $notes = null): bool
    {
        try {
            return DB::transaction(function () use ($userRankId, $adminId, $notes) {
                $row = FomUserRank::lockForUpdate()->find($userRankId);
                if (!$row || $row->status !== 'pending') {
                    return false;
                }

                $rank = FomRank::find($row->fom_rank_id);
                $reward = $rank ? (float) $rank->reward : 0.0;

                if ($reward > 0) {
                    ChartAccount::creditLocked($row->user_id, 'CASHOUT', $reward, "FOM rank reward: {$row->rank_name}");
                }

                $row->update([
                    'status'      => 'approved',
                    'reviewed_at' => Carbon::now(),
                    'reviewed_by' => $adminId,
                    'reward_paid' => $reward,
                    'admin_notes' => $notes,
                ]);

                try {
                    \App\Models\Transaction::create([
                        'user_id'             => $row->user_id,
                        'transaction_no'      => method_exists(\App\Models\Transaction::class, 'generateTransactionNo')
                            ? \App\Models\Transaction::generateTransactionNo()
                            : 'FOM-RANK-' . time() . '-' . rand(100, 999),
                        'transaction_type'    => 'FOM_RANK_REWARD',
                        'transaction_details' => json_encode([
                            'rank'   => $row->rank_name,
                            'level'  => $row->rank_level,
                            'reward' => $reward,
                            'extras' => $rank->extra_bonuses ?? '',
                        ]),
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('FOM rank reward transaction log failed: ' . $e->getMessage());
                }

                return true;
            });
        } catch (\Throwable $e) {
            Log::error("FomRankService approve failed for row #{$userRankId}: " . $e->getMessage());
            return false;
        }
    }

    public static function reject(int $userRankId, int $adminId, ?string $notes = null): bool
    {
        try {
            $row = FomUserRank::find($userRankId);
            if (!$row || $row->status !== 'pending') {
                return false;
            }
            $row->update([
                'status'      => 'rejected',
                'reviewed_at' => Carbon::now(),
                'reviewed_by' => $adminId,
                'admin_notes' => $notes,
            ]);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
