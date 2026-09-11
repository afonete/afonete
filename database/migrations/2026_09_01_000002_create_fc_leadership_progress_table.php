<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * FC Leadership bonus progress + referral_bonuses status/source expansion.
     *
     * New source values:
     *   - fc_direct_vb   : informational row recording the +100 VB credit per
     *                      direct FC referral (non-cash; shown in referral history).
     *   - fc_leadership  : lump-sum milestone tier reward (3/15/40/150/750/2000 VB
     *                      converted to USD and sent to cashout on Monday).
     *
     * New status value:
     *   - vb_only        : non-cash informational row (the +100VB credits). These
     *                      never become withdrawable cash.
     *
     * To avoid MySQL's strict ENUM woes (truncation warnings when old data has
     * unexpected values, or strict-mode failures when modifying), we convert
     * both columns to VARCHAR with CHECK-free plain strings. Status/source are
     * already validated in code; ENUM was adding no value here.
     */
    public function up(): void
    {
        if (!Schema::hasTable('fc_leadership_progress')) {
            Schema::create('fc_leadership_progress', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();
                $table->unsignedInteger('direct_fc_count')->default(0)
                    ->comment('Lifetime count of direct referrals who purchased an FC VIP package (status=1).');
                $table->unsignedInteger('volume_bonus_vb')->default(0)
                    ->comment('Lifetime FC Volume Bonus pool (100 VB per direct FC referral).');
                $table->unsignedTinyInteger('highest_tier_paid')->default(0)
                    ->comment('Highest FC Leadership tier already paid (0 = none, 1..6).');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('referral_bonuses')) {
            // Convert ENUM → VARCHAR to avoid data-truncation warnings when new
            // statuses ('vb_only') or sources ('fc_direct_vb', 'fc_leadership')
            // are introduced. Idempotent: check the column type first.
            // DB::select returns stdClass rows; MySQL SHOW COLUMNS yields
            // uppercase property names (Field, Type, Null, Key, ...).
            foreach (['status', 'source'] as $col) {
                $rows = DB::select("SHOW COLUMNS FROM `referral_bonuses` LIKE '{$col}'");
                if (empty($rows)) {
                    continue;
                }
                $type = strtolower((string) ($rows[0]->Type ?? ''));
                if (stripos($type, 'varchar') === false && stripos($type, 'text') === false) {
                    if ($col === 'status') {
                        DB::statement("ALTER TABLE `referral_bonuses` MODIFY COLUMN `status` VARCHAR(20) NOT NULL DEFAULT 'pending'");
                    } else {
                        DB::statement("ALTER TABLE `referral_bonuses` MODIFY COLUMN `source` VARCHAR(30) NOT NULL DEFAULT 'referral'");
                    }
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fc_leadership_progress');
        // Leave columns as VARCHAR — reversing would risk data loss if new
        // values have been stored. Down() exists mainly to drop the new table.
    }
};
