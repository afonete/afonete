<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\FomLicenceMiner;
use App\Models\ReferralBonus;
use App\Models\ChartAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * FOM Licence Miner referral plan — binary weekly volume + direct sponsors.
 *
 * AT PURCHASE (buyer = L0 activates a FOM package):
 *   1. VOLUME ACCRUAL — each upline level accrues a share of the package's
 *      volume_bonus into the LEFT or RIGHT volume balance of that upline
 *      user (the side is the leg of their binary tree the buyer sits in):
 *          L1 = 100% · L2 = 100% · L3–L4 = 50% · L5–L10 = 20%
 *      Balances: ChartAccount FOM_VOL_LEFT / FOM_VOL_RIGHT.
 *   2. DIRECT SPONSORS — L1 (direct sponsor) earns the direct_sponsors %
 *      of THEIR OWN highest active FOM package (at referral time) applied
 *      to the price paid. Stored as an 'accrued' ReferralBonus row — NOT
 *      paid instantly; paid on Monday.
 *   3. VOLUME POINTS — the package's volume_point credits the L1 sponsor's
 *      VOLUME_POINT wallet (volume tracking, not commission).
 *
 * EVERY MONDAY (fom:process-weekly):
 *   For each eligible user: weaker = min(FOM_VOL_LEFT, FOM_VOL_RIGHT).
 *   THE MATCH IS THE TRIGGER: if either side is 0 (weaker = 0) NO referral
 *   payout is generated at all — direct-sponsor accruals and both side
 *   volumes wait untouched for a future match. When weaker > 0:
 *   binary payout = weaker × 10% (Affiliate V.bonus — all packages 10%),
 *   the matched amount is deducted from BOTH sides (surplus carries
 *   forward), and payout + ALL accrued Direct Sponsor bonuses transfer
 *   TOGETHER to the CASHOUT balance.
 *
 * ELIGIBILITY GATE (both at purchase for the direct row, and at payout):
 *   affiliate terms accepted (affiliate_terms_accepted_at) or binary_status
 *   = active. Ineligible direct sponsors get a VISIBLE zero 'ineligible'
 *   row (they see the referral, earn nothing). Ineligible users are
 *   skipped on Monday — their volumes/accruals wait until they accept.
 */
class FomReferralService
{
    /** Volume accrual share per level (L1..L10), in percent of volume_bonus. */
    public const LEVEL_SHARES = [
        1  => 100.0,
        2  => 100.0,
        3  => 50.0,
        4  => 50.0,
        5  => 20.0,
        6  => 20.0,
        7  => 20.0,
        8  => 20.0,
        9  => 20.0,
        10 => 20.0,
    ];

    /** Affiliate V.bonus — the weekly rate applied to the weaker side. */
    public const AFFILIATE_VBONUS_RATE = 10.0; // all packages have 10%

    public const ACC_VOL_LEFT  = 'FOM_VOL_LEFT';
    public const ACC_VOL_RIGHT = 'FOM_VOL_RIGHT';

    /** Per-request memo for the schema self-heal. */
    protected static $schemaEnsured = false;

    /**
     * Self-heal: referral_bonuses.status was created as a MySQL ENUM of the
     * UVP statuses only. The FOM plan writes 'accrued'/'volume'/'ineligible'
     * /'paid', which an enum column REJECTS — every FOM insert would throw
     * and be swallowed by the per-level try/catch, making bonuses vanish
     * after activation. Widen to VARCHAR(30) when the enum is detected.
     */
    public static function ensureBonusStatusColumn(): void
    {
        if (static::$schemaEnsured) {
            return;
        }
        static::$schemaEnsured = true;

        try {
            $conn = \Illuminate\Support\Facades\Schema::getConnection();
            if ($conn->getDriverName() !== 'mysql') {
                return; // SQLite/others store enum as unconstrained text
            }

            $col = $conn->selectOne("SHOW COLUMNS FROM `referral_bonuses` LIKE 'status'");
            if ($col && stripos((string) $col->Type, 'enum(') === 0) {
                $conn->statement("ALTER TABLE `referral_bonuses` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'pending'");
                Log::warning('FomReferralService: widened referral_bonuses.status from ENUM to VARCHAR(30) (FOM statuses were being rejected).');
            }
        } catch (\Throwable $e) {
            Log::error('FomReferralService::ensureBonusStatusColumn failed: ' . $e->getMessage());
        }
    }

    /** Has this user accepted the affiliate terms / activated binary status? */
    public static function isAffiliateEligible(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return !empty($user->affiliate_terms_accepted_at)
            || strtolower(trim((string) ($user->binary_status ?? ''))) === 'active';
    }

    /**
     * The direct_sponsors % of the sponsor's OWN highest active FOM package
     * (by price) at this moment. 0 when they hold no active FOM package.
     */
    public static function directSponsorRate(User $sponsor): float
    {
        try {
            $payments = Payment::where('user', $sponsor->id)
                ->where('status', 1)
                ->where('is_expired', false)
                ->get()
                ->filter(fn ($p) => $p->isFom());

            $best = null;
            foreach ($payments as $p) {
                $pkg = FomLicenceMiner::whereRaw('UPPER(name) = ?', [strtoupper(trim((string) $p->package))])->first();
                if ($pkg && (!$best || FomLicenceMiner::cleanNum($pkg->price) > FomLicenceMiner::cleanNum($best->price))) {
                    $best = $pkg;
                }
            }

            return $best ? FomLicenceMiner::cleanNum($best->direct_sponsors) : 0.0;
        } catch (\Throwable $e) {
            Log::error('FomReferralService::directSponsorRate failed: ' . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * The binary side (LEFT/RIGHT) of $child within $owner's team.
     * Falls back to LEFT when no placement record exists.
     */
    public static function sideOf(User $owner, User $child): string
    {
        try {
            $team = \App\Models\Teams::where('user_id', $owner->id)
                ->where('team_user_id', $child->id)
                ->first();
            $side = strtoupper(trim((string) ($team->side ?? '')));
            return in_array($side, ['LEFT', 'RIGHT'], true) ? $side : 'LEFT';
        } catch (\Throwable $e) {
            return 'LEFT';
        }
    }

    /**
     * Self-heal: scan ALL confirmed FOM payments and credit any missing
     * referral accruals (legacy purchases made before this engine was wired,
     * or paths that failed mid-request). Safe to run repeatedly —
     * creditForFomPurchase() is idempotent per referrer+payment+level.
     *
     * @return int number of bonus rows created
     */
    public static function syncMissingFomBonuses(): int
    {
        $created = 0;

        try {
            $payments = Payment::where('status', 1)->get()->filter(fn ($p) => $p->isFom());

            foreach ($payments as $payment) {
                try {
                    $rows = self::creditForFomPurchase($payment);
                    $created += count($rows);

                    // Newly-healed activations must also enter the incentive
                    // program (idempotent per user+tier+anchor), otherwise
                    // legacy purchases could reach an achievement invisibly.
                    if (count($rows) > 0) {
                        \App\Services\FomIncentiveService::onFomActivation($payment);
                    }
                } catch (\Throwable $e) {
                    Log::error("syncMissingFomBonuses failed for payment #{$payment->id}: " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('syncMissingFomBonuses failed: ' . $e->getMessage());
        }

        return $created;
    }

    /**
     * AT PURCHASE: accrue per-level side volumes, record the L1 Direct
     * Sponsors bonus (accrued — paid Monday), credit L1 volume points.
     * Idempotent per payment. Returns created ReferralBonus rows.
     */
    public static function creditForFomPurchase(?Payment $payment): array
    {
        if (!$payment || !$payment->isFom()) {
            return [];
        }

        self::ensureBonusStatusColumn();

        $buyer = User::find($payment->user);
        if (!$buyer) {
            Log::warning("FomReferralService: buyer #{$payment->user} not found for payment #{$payment->id} — no referral credit.");
            return [];
        }

        $pkg = FomLicenceMiner::whereRaw('UPPER(name) = ?', [strtoupper(trim((string) $payment->package))])->first();
        if (!$pkg) {
            Log::warning("FomReferralService: package '{$payment->package}' not found in fom_licence_miners for payment #{$payment->id} — no referral credit.");
            return [];
        }

        $pricePaid   = (float) ($payment->paid ?? $payment->amount ?? 0);
        $volumeBonus = FomLicenceMiner::cleanNum($pkg->volume_bonus ?? 0);
        $volumePoint = FomLicenceMiner::cleanNum($pkg->volume_point ?? 0);

        // Auto-repair buyer's referee_id from Teams (platform convention)
        if (empty($buyer->referee_id) || (int) $buyer->referee_id === 0) {
            $team = \App\Models\Teams::where('team_user_id', $buyer->id)->first();
            if ($team && $team->user_id) {
                $buyer->referee_id = $team->user_id;
                $buyer->save();
                $buyer->refresh();
            }
        }

        $created = [];
        $child   = $buyer; // the previous node while walking up the chain
        $upline  = $buyer->uplineChain(10);
        $seen    = [$buyer->id => true]; // cycle guard: never pay the buyer or revisit a node

        foreach ($upline as $slot) {
            $referrer = $slot['user'];
            $level    = (int) $slot['level'];
            if (!$referrer || $level < 1 || $level > 10) {
                continue;
            }

            // Cycle / self-referral guard: a corrupted referee chain
            // (A→B→A) must never let the buyer earn on their own purchase
            // or the same upline earn twice on it.
            if (isset($seen[$referrer->id])) {
                Log::warning("FomReferralService: referral cycle detected at user #{$referrer->id} for payment #{$payment->id}; chain walk stopped.");
                break;
            }
            $seen[$referrer->id] = true;

            // Free users never earn (existing platform rule)
            if (ReferralService::isFreeUser($referrer)) {
                $child = $referrer;
                continue;
            }

            // Idempotency: one accrual per referrer+payment+level
            $already = ReferralBonus::where('user_id', $referrer->id)
                ->where('source_payment_id', $payment->id)
                ->where('level', $level)
                ->where('source', 'fom_referral')
                ->exists();
            if ($already) {
                $child = $referrer;
                continue;
            }

            $share     = self::LEVEL_SHARES[$level] ?? 0.0;
            $volAccrue = round($volumeBonus * ($share / 100), 4);
            $side      = self::sideOf($referrer, $child);

            $directRate  = 0.0;
            $directBonus = 0.0;
            if ($level === 1) {
                $directRate  = self::directSponsorRate($referrer);
                $directBonus = round($pricePaid * ($directRate / 100), 4);
            }

            $eligible = self::isAffiliateEligible($referrer);

            // Direct Sponsors: accrued when eligible (paid Monday), visible
            // zero 'ineligible' row when not. Volume rows are informational.
            $status = $level === 1
                ? ($eligible ? 'accrued' : 'ineligible')
                : 'volume';

            $notes = "FOM L{$level} from " . ($buyer->name ?? $buyer->user ?? $buyer->email)
                . " ({$pkg->name}) · volume {$share}% of {$volumeBonus} = {$volAccrue} → {$side}"
                . ($level === 1 ? " · direct {$directRate}% of \${$pricePaid} = {$directBonus}" : '')
                . ($level === 1 && !$eligible ? ' · FORFEITED: affiliate terms not accepted / binary status inactive' : '');

            try {
                $row = DB::transaction(function () use ($referrer, $buyer, $payment, $level, $share, $pricePaid, $directBonus, $eligible, $status, $notes, $volAccrue, $volumePoint, $side, $pkg) {
                    $row = ReferralBonus::create([
                        'user_id'           => $referrer->id,
                        'source_user_id'    => $buyer->id,
                        'source_payment_id' => $payment->id,
                        'level'             => $level,
                        'percentage'        => $share,
                        'source_amount'     => $pricePaid,
                        'bonus_amount'      => ($level === 1 && $eligible) ? $directBonus : 0,
                        'week_start'        => ReferralService::nextMonday(),
                        'status'            => $status,
                        'source'            => 'fom_referral',
                        'source_ref'        => 'FOM-L' . $level . '-' . $side,
                        'notes'             => $notes,
                    ]);

                    // Side volume accrual (all levels; payout gated on Monday)
                    if ($volAccrue > 0) {
                        ChartAccount::creditLocked(
                            $referrer->id,
                            $side === 'RIGHT' ? self::ACC_VOL_RIGHT : self::ACC_VOL_LEFT,
                            $volAccrue,
                            "FOM L{$level} volume from payment #{$payment->id}"
                        );
                    }

                    // Volume Points → direct sponsor only
                    if ($level === 1 && $volumePoint > 0) {
                        ChartAccount::creditLocked($referrer->id, 'VOLUME_POINT', $volumePoint, "Volume points for {$pkg->name}, payment #{$payment->id}");
                    }

                    return $row;
                });

                $created[] = $row;
            } catch (\Throwable $e) {
                Log::error("FomReferralService L{$level} accrual failed for payment #{$payment->id}: " . $e->getMessage());
            }

            $child = $referrer;
        }

        return $created;
    }

    /**
     * MONDAY: for every user with side volume or accrued direct bonuses —
     *   payout = min(left, right) × 10%  +  sum(accrued Direct Sponsors)
     * The matched amount is deducted from BOTH sides (surplus carries
     * forward). Total transfers to CASHOUT. Ineligible users are skipped
     * entirely (their volumes/accruals wait). Returns users paid.
     */
    public static function processWeekly(): int
    {
        $paidUsers = 0;

        // Only users with volume on a leg can possibly trigger a match
        // (accrued direct bonuses alone never pay — the binary match is the
        // payout trigger), so candidates are volume holders only.
        $userIds = ChartAccount::whereIn('acc_type', [self::ACC_VOL_LEFT, self::ACC_VOL_RIGHT])
            ->where('amount', '>', 0)
            ->pluck('user_id')
            ->unique()
            ->values();

        foreach ($userIds as $uid) {
            $user = User::find($uid);
            if (!$user) {
                continue;
            }

            // Gate at payout time: no commission until terms are accepted.
            if (!self::isAffiliateEligible($user) || ReferralService::isFreeUser($user)) {
                continue;
            }

            try {
                DB::transaction(function () use ($user, &$paidUsers) {
                    $left  = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', self::ACC_VOL_LEFT)->lockForUpdate()->sum('amount');
                    $right = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', self::ACC_VOL_RIGHT)->lockForUpdate()->sum('amount');

                    // BINARY MATCH IS THE PAYOUT TRIGGER: if either side is 0
                    // there is NO match and NO referral payout is generated at
                    // all this Monday — the direct-sponsor accruals and both
                    // side volumes stay untouched and wait for a future match.
                    $weaker = min($left, $right);
                    if ($weaker <= 0) {
                        return;
                    }

                    $binaryPayout = round($weaker * (self::AFFILIATE_VBONUS_RATE / 100), 4);

                    // Accrued Direct Sponsors rows → paid together with the match
                    $accrued = ReferralBonus::where('user_id', $user->id)
                        ->where('source', 'fom_referral')
                        ->where('status', 'accrued')
                        ->lockForUpdate()
                        ->get();
                    $directTotal = round((float) $accrued->sum('bonus_amount'), 4);

                    $total = round($binaryPayout + $directTotal, 4);

                    // Deduct the matched volume from BOTH sides
                    if ($weaker > 0) {
                        ChartAccount::debitLocked($user->id, self::ACC_VOL_LEFT, $weaker, false, 'FOM weekly binary match');
                        ChartAccount::debitLocked($user->id, self::ACC_VOL_RIGHT, $weaker, false, 'FOM weekly binary match');
                    }

                    // Transfer to CASHOUT
                    if ($total > 0) {
                        ChartAccount::creditLocked($user->id, 'CASHOUT', $total, 'FOM weekly payout (binary + direct sponsors)');
                    }

                    foreach ($accrued as $row) {
                        $row->update(['status' => 'paid', 'withdrawn_at' => now()]);
                    }

                    // Audit trail
                    try {
                        \App\Models\Transaction::create([
                            'user_id'             => $user->id,
                            'transaction_no'      => method_exists(\App\Models\Transaction::class, 'generateTransactionNo')
                                ? \App\Models\Transaction::generateTransactionNo()
                                : 'FOM-WK-' . time() . '-' . rand(100, 999),
                            'transaction_type'    => 'FOM_WEEKLY_REFERRAL_PAYOUT',
                            'transaction_details' => json_encode([
                                'left_volume'    => $left,
                                'right_volume'   => $right,
                                'weaker_side'    => $left <= $right ? 'LEFT' : 'RIGHT',
                                'matched'        => $weaker,
                                'binary_payout'  => $binaryPayout,
                                'direct_bonuses' => $directTotal,
                                'total_to_cashout' => $total,
                                'rate'           => self::AFFILIATE_VBONUS_RATE,
                                'date'           => date('Y-m-d H:i:s'),
                            ]),
                        ]);
                    } catch (\Throwable $e) {
                        Log::warning('FOM weekly payout transaction log failed: ' . $e->getMessage());
                    }

                    $paidUsers++;
                });
            } catch (\Throwable $e) {
                Log::error("FomReferralService::processWeekly failed for user #{$uid}: " . $e->getMessage());
            }
        }

        return $paidUsers;
    }
}
