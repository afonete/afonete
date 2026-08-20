<?php

namespace App\Services;

use App\Models\User;
use App\Models\ChartAccount;
use App\Models\FomResidualLevel;
use App\Models\FomResidualApproval;
use App\Models\FomUserRank;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Residual Income Matching Bonus engine.
 *
 * Gate: user must hold an APPROVED FOM rank (≥ Trainee).
 * MATRIX (referral structure, referee_id chain):
 *   L1 = the FIRST `per_parent(L1)`=4 ranked users found in the leader's
 *        downline (nearest-first, then registration order). "Ranked" =
 *        approved FOM rank.
 *   L2..L6 = for each member of the previous level, their FIRST
 *        `per_parent`=2 ranked downlines. Level is COMPLETE (eligible)
 *        only when required_members are present AND every previous-level
 *        member contributed their full per_parent quota.
 * INCOME: every Monday (after FOM weekly payout) the leader earns
 *   income_percent of each level-member's FOM Affiliate Bonus paid that
 *   run (FOM_WEEKLY_REFERRAL_PAYOUT total_to_cashout) → COMMISSION wallet.
 *   Levels pay independently: L1 eligible pays L1 even if L2 incomplete.
 */
class FomResidualService
{
    /** User's approved-rank gate (≥ Trainee = any approved FOM rank). */
    public static function hasRank(int $userId): bool
    {
        return FomUserRank::highestApprovedLevel($userId) >= 1;
    }

    /**
     * FIRST $take ranked users in $userId's referral downline, scanned
     * LEVEL BY LEVEL (an entire depth is completed — ordered by id, i.e.
     * oldest account first — before descending), excluding $exclude ids.
     * Level-complete scanning keeps the selection stable when deeper
     * branches grow: a depth-2 ranked user is always picked before any
     * depth-3 one regardless of which branch they sit in.
     * @return int[] user ids
     */
    public static function firstRankedDownlines(int $userId, int $take, array &$exclude): array
    {
        $found = [];
        $visited = [$userId => true];
        foreach ($exclude as $x) {
            $visited[$x] = true;
        }
        $currentLevel = [$userId];

        try {
            while (!empty($currentLevel) && count($found) < $take) {
                // Collect the ENTIRE next depth, ordered by id.
                $nextLevel = [];
                foreach (array_chunk($currentLevel, 500) as $chunk) {
                    foreach (User::whereIn('referee_id', $chunk)->orderBy('id')->pluck('id') as $cid) {
                        $cid = (int) $cid;
                        if (!isset($visited[$cid])) {
                            $visited[$cid] = true;
                            $nextLevel[] = $cid;
                        }
                    }
                }
                sort($nextLevel);

                // Pick ranked users from this whole depth before descending.
                foreach ($nextLevel as $cid) {
                    if (count($found) >= $take) {
                        break;
                    }
                    if (self::hasRank($cid)) {
                        $found[] = $cid;
                        $exclude[] = $cid;
                    }
                }

                $currentLevel = $nextLevel;
            }
        } catch (\Throwable $e) {
            Log::error("FomResidualService firstRankedDownlines failed for #{$userId}: " . $e->getMessage());
        }

        return $found;
    }

    /**
     * Build the full matching matrix for a leader.
     * @return array{eligible_level: int, levels: array<int, array{members: int[], complete: bool, required: int, income_percent: float}>}
     */
    public static function matrix(int $userId): array
    {
        FomResidualLevel::ensureTableAndData();

        $result = ['eligible_level' => 0, 'levels' => []];

        if (!self::hasRank($userId)) {
            return $result;
        }

        $used = [$userId];
        $prevMembers = null;
        $chainComplete = true;

        foreach (FomResidualLevel::ordered()->get() as $cfg) {
            $required  = (int) $cfg->required_members;
            $perParent = (int) $cfg->per_parent;
            $members   = [];
            $complete  = false;

            if ($cfg->level === 1) {
                $members  = self::firstRankedDownlines($userId, $perParent, $used);
                $complete = count($members) >= $required;
            } elseif (is_array($prevMembers)) {
                $allParentsFilled = count($prevMembers) > 0;
                foreach ($prevMembers as $parentId) {
                    $got = self::firstRankedDownlines((int) $parentId, $perParent, $used);
                    $members = array_merge($members, $got);
                    if (count($got) < $perParent) {
                        $allParentsFilled = false;
                    }
                }
                $complete = $allParentsFilled && count($members) >= $required;
            }

            $result['levels'][$cfg->level] = [
                'members'        => $members,
                'required'       => $required,
                'per_parent'     => $perParent,
                'income_percent' => (float) $cfg->income_percent,
                'complete'       => $complete,
            ];

            $chainComplete = $chainComplete && $complete;
            if ($chainComplete) {
                $result['eligible_level'] = $cfg->level;
            }

            $prevMembers = $members;
            if (empty($members)) {
                break; // nothing deeper is possible
            }
        }

        return $result;
    }

    /**
     * A member's FOM Affiliate Bonus paid in the window
     * (FOM_WEEKLY_REFERRAL_PAYOUT → total_to_cashout).
     */
    public static function affiliateBonusInWindow(int $userId, Carbon $from, Carbon $to): float
    {
        try {
            $sum = 0.0;
            $rows = \App\Models\Transaction::where('user_id', $userId)
                ->where('transaction_type', 'FOM_WEEKLY_REFERRAL_PAYOUT')
                ->whereBetween('created_at', [$from, $to])
                ->get();
            foreach ($rows as $t) {
                $d = json_decode((string) $t->transaction_details, true) ?: [];
                $sum += (float) ($d['total_to_cashout'] ?? 0);
            }
            return $sum;
        } catch (\Throwable $e) {
            return 0.0;
        }
    }

    /**
     * Detect COMPLETE levels for a leader → PENDING approval rows for the
     * admin. Sequential: a level is only submitted when the previous one is
     * already approved (or it is L1). Idempotent per leader+level.
     * @return int rows created
     */
    public static function detectApprovalsFor(int $userId): int
    {
        FomResidualApproval::ensureTable();

        $created = 0;

        try {
            $m = self::matrix($userId);
            foreach ($m['levels'] as $level => $info) {
                if (!$info['complete']) {
                    break; // chain broken — nothing deeper can be submitted
                }
                if (FomResidualApproval::where('user_id', $userId)->where('level', $level)->exists()) {
                    continue;
                }
                // sequential gate: L2+ needs the previous level approved
                if ($level > 1 && FomResidualApproval::approvedLevel($userId) < $level - 1) {
                    break;
                }

                $names = User::whereIn('id', $info['members'])->get()
                    ->map(fn ($u) => $u->user ?? $u->name)->all();

                FomResidualApproval::create([
                    'user_id'          => $userId,
                    'level'            => $level,
                    'status'           => 'pending',
                    'members_snapshot' => ['ids' => $info['members'], 'names' => $names],
                    'detected_at'      => Carbon::now(),
                ]);
                $created++;
            }
        } catch (\Throwable $e) {
            Log::error("FomResidualService detectApprovalsFor failed for #{$userId}: " . $e->getMessage());
        }

        return $created;
    }

    /** Sweep all ranked leaders for new pending level approvals. */
    public static function detectApprovalsAll(): int
    {
        $created = 0;
        try {
            foreach (FomUserRank::where('status', 'approved')->pluck('user_id')->unique() as $lid) {
                $created += self::detectApprovalsFor((int) $lid);
            }
        } catch (\Throwable $e) {
            Log::error('FomResidualService detectApprovalsAll failed: ' . $e->getMessage());
        }
        return $created;
    }

    /** ADMIN: approve a pending level. */
    public static function approveLevel(int $approvalId, int $adminId, ?string $notes = null): bool
    {
        try {
            $row = FomResidualApproval::find($approvalId);
            if (!$row || $row->status !== 'pending') {
                return false;
            }
            $row->update([
                'status'      => 'approved',
                'reviewed_at' => Carbon::now(),
                'reviewed_by' => $adminId,
                'admin_notes' => $notes,
            ]);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /** ADMIN: reject a pending level. */
    public static function rejectLevel(int $approvalId, int $adminId, ?string $notes = null): bool
    {
        try {
            $row = FomResidualApproval::find($approvalId);
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

    /**
     * MONDAY: pay every leader their matching income for this week's FOM
     * Affiliate Bonuses — ONLY for levels the ADMIN has APPROVED (per-level
     * gate, user spec §68), straight to CASHOUT. A level pays the % on each
     * approved member's bonus individually (per-member breakdown recorded).
     * Runs AFTER FomReferralService::processWeekly() so the payout
     * transactions exist. Idempotent per leader+week.
     *
     * @return int leaders paid
     */
    public static function processWeekly(?Carbon $now = null): int
    {
        FomResidualLevel::ensureTableAndData();
        FomResidualApproval::ensureTable();

        $now  = $now ?: Carbon::now();
        $from = $now->copy()->startOfWeek();
        $to   = $now->copy()->endOfWeek();
        $weekKey = $from->toDateString();

        $paid = 0;

        try {
            // Refresh pending approvals first so the admin queue is current.
            self::detectApprovalsAll();

            // Only leaders with at least one ADMIN-APPROVED level can be paid.
            $leaderIds = FomResidualApproval::where('status', 'approved')->pluck('user_id')->unique();

            foreach ($leaderIds as $lid) {
                $lid = (int) $lid;

                // Idempotency: one run per leader per week.
                $already = \App\Models\Transaction::where('user_id', $lid)
                    ->where('transaction_type', 'FOM_RESIDUAL_MATCHING')
                    ->where('transaction_details', 'like', '%"week":"' . $weekKey . '"%')
                    ->exists();
                if ($already) {
                    continue;
                }

                $approvedLevel = FomResidualApproval::approvedLevel($lid);
                if ($approvedLevel < 1) {
                    continue;
                }

                $m = self::matrix($lid);
                // Pay up to the LOWER of (structure still eligible, admin approved)
                $payLevel = min($approvedLevel, $m['eligible_level']);
                if ($payLevel < 1) {
                    continue;
                }

                $total = 0.0;
                $breakdown = [];
                foreach ($m['levels'] as $level => $info) {
                    if ($level > $payLevel) {
                        break;
                    }
                    // Per-member income (user spec: "% for each person in
                    // case he got the bonus")
                    $levelSum = 0.0;
                    $perMember = [];
                    foreach ($info['members'] as $mid) {
                        $memberBonus = self::affiliateBonusInWindow((int) $mid, $from, $to);
                        if ($memberBonus > 0) {
                            $memberIncome = round($memberBonus * ($info['income_percent'] / 100), 4);
                            $perMember[] = ['user_id' => (int) $mid, 'bonus' => $memberBonus, 'income' => $memberIncome];
                            $levelSum += $memberBonus;
                        }
                    }
                    $income = round($levelSum * ($info['income_percent'] / 100), 4);
                    if ($income > 0) {
                        $breakdown[] = ['level' => $level, 'members_bonus' => $levelSum, 'percent' => $info['income_percent'], 'income' => $income, 'per_member' => $perMember];
                        $total += $income;
                    }
                }

                if ($total <= 0) {
                    continue;
                }

                DB::transaction(function () use ($lid, $total, $breakdown, $weekKey, $payLevel) {
                    // User spec §68: income goes DIRECTLY TO CASHOUT.
                    ChartAccount::creditLocked($lid, 'CASHOUT', $total, "Residual matching bonus week {$weekKey}");
                    \App\Models\Transaction::create([
                        'user_id'             => $lid,
                        'transaction_no'      => method_exists(\App\Models\Transaction::class, 'generateTransactionNo')
                            ? \App\Models\Transaction::generateTransactionNo()
                            : 'FOM-RES-' . time() . '-' . rand(100, 999),
                        'transaction_type'    => 'FOM_RESIDUAL_MATCHING',
                        'transaction_details' => json_encode([
                            'week'           => $weekKey,
                            'eligible_level' => $payLevel,
                            'total'          => $total,
                            'breakdown'      => $breakdown,
                        ]),
                    ]);
                });

                $paid++;
            }
        } catch (\Throwable $e) {
            Log::error('FomResidualService processWeekly failed: ' . $e->getMessage());
        }

        return $paid;
    }

    /**
     * Interactive Team Tree data: nested nodes of the leader's matching
     * structure (levels → members with username, rank, level membership).
     * Children = each member's OWN contribution to the next level.
     */
    public static function teamTree(int $userId): array
    {
        $m = self::matrix($userId);
        $root = User::find($userId);

        $node = function (int $uid, int $level) {
            $u = User::find($uid);
            return [
                'id'       => $uid,
                'name'     => $u ? ($u->user ?? $u->name) : ('#' . $uid),
                'rank'     => \App\Models\FomUserRank::where('user_id', $uid)->where('status', 'approved')->orderByDesc('rank_level')->value('rank_name') ?: 'No rank',
                'level'    => $level,
                'children' => [],
            ];
        };

        $tree = [
            'id'       => $userId,
            'name'     => $root ? ($root->user ?? $root->name) : ('#' . $userId),
            'rank'     => \App\Models\FomUserRank::where('user_id', $userId)->where('status', 'approved')->orderByDesc('rank_level')->value('rank_name') ?: 'No rank',
            'level'    => 0,
            'children' => [],
        ];

        // Build parent → children map: replay firstRankedDownlines per parent
        // exactly as matrix() does so the tree mirrors the paying structure.
        $used = [$userId];
        $levels = $m['levels'];
        if (empty($levels)) {
            return $tree;
        }

        $nodesById = [];
        foreach ($levels[1]['members'] ?? [] as $mid) {
            $n = $node((int) $mid, 1);
            $nodesById[$mid] = $n;
            $tree['children'][] = $mid;
        }
        $usedTree = array_merge([$userId], $levels[1]['members'] ?? []);
        $prev = $levels[1]['members'] ?? [];

        foreach ($levels as $level => $info) {
            if ($level === 1 || empty($prev)) {
                $prev = $info['members'] ?? $prev;
                continue;
            }
            $perParent = (int) ($info['per_parent'] ?? 2);
            foreach ($prev as $parentId) {
                $got = self::firstRankedDownlines((int) $parentId, $perParent, $usedTree);
                foreach ($got as $cid) {
                    $n = $node((int) $cid, $level);
                    $nodesById[$cid] = $n;
                    $nodesById[$parentId]['children'][] = $cid;
                }
            }
            $prev = $info['members'] ?? [];
        }

        // Materialize nested arrays
        $materialize = function ($id) use (&$materialize, &$nodesById) {
            $n = $nodesById[$id];
            $n['children'] = array_map($materialize, $n['children']);
            return $n;
        };
        $tree['children'] = array_map($materialize, $tree['children']);

        return $tree;
    }
}
