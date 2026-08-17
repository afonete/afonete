<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * referral_bonuses.status was an ENUM('pending','withdrawable','withdrawn',
 * 'expired','reversed'). The FOM referral plan writes additional statuses —
 * 'accrued', 'volume', 'ineligible', 'paid' — which MySQL REJECTS on an
 * enum column (silently killing every FOM bonus insert in production while
 * SQLite tests passed). Widen the column to VARCHAR(30).
 */
class ChangeReferralBonusesStatusToString extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('referral_bonuses')) {
            return;
        }

        try {
            $driver = Schema::getConnection()->getDriverName();

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE `referral_bonuses` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'pending'");
            }
            // SQLite stores enums as plain TEXT with no constraint in the
            // versions used here — nothing to change.
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('change_referral_bonuses_status migration failed: ' . $e->getMessage());
        }
    }

    public function down()
    {
        // Down-migration intentionally left as VARCHAR: narrowing back to the
        // old enum would destroy FOM rows carrying the new statuses.
    }
}
