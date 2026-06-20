<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Extend withdrawals to support all 3 manual withdrawal methods
 * that mirror the deposit options:
 *
 *   1. Crypto (USDT/BTC/ETH/BNB) — wallet_address + network (TRC-20/ERC-20/BEP-20)
 *   2. Advcash                      — account number stored in wallet_address, currency=USD/EUR
 *   3. Perfect Money                — account number stored in wallet_address, currency=USD/EUR
 *
 * Previously the withdrawals table assumed:
 *   - currency is always USDT
 *   - address is always a TRC-20 wallet
 *   - withdrawals are either manual (admin approves) OR auto (Plisio)
 *
 * Now every withdrawal has:
 *   - method     : crypto | advcash | perfect_money
 *   - network    : TRC-20 | ERC-20 | BEP-20 | POLYGON | BITCOIN | ADVCASH | PERFECT_MONEY
 *   - currency   : USDT | BTC | ETH | BNB | USD | EUR
 *   - notes      : user-supplied notes (e.g. "please send before Friday")
 *   - processed_by / processed_at : which admin acted + when
 *
 * NOTE: Uses raw SQL `DB::statement` for column modifications instead
 * of Schema Builder's `->change()`. Sidesteps doctrine/dbal version
 * incompatibility with Laravel 8.
 */
class ExtendWithdrawalsForManualMethods extends Migration
{
    public function up()
    {
        $driver = DB::connection()->getDriverName();

        // `method` discriminator
        if (!Schema::hasColumn('withdrawals', 'method')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `withdrawals`
                    ADD COLUMN `method` VARCHAR(20) NOT NULL DEFAULT 'crypto'
                    COMMENT 'crypto | advcash | perfect_money' AFTER `user_id`");
            } else {
                DB::statement("ALTER TABLE withdrawals ADD COLUMN method VARCHAR(20) NOT NULL DEFAULT 'crypto'");
            }
        }

        // `network` (nullable — only crypto rows need it)
        if (!Schema::hasColumn('withdrawals', 'network')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `withdrawals`
                    ADD COLUMN `network` VARCHAR(30) NULL
                    COMMENT 'TRC-20 | ERC-20 | BEP-20 | POLYGON | BITCOIN | ADVCASH | PERFECT_MONEY'
                    AFTER `method`");
            } else {
                DB::statement("ALTER TABLE withdrawals ADD COLUMN network VARCHAR(30) NULL");
            }
        }

        // Make `notes` explicitly nullable (column already exists per the
        // original create migration; we just normalise its nullability).
        if (Schema::hasColumn('withdrawals', 'notes')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `withdrawals` MODIFY COLUMN `notes` TEXT NULL");
            } elseif ($driver === 'pgsql') {
                DB::statement("ALTER TABLE withdrawals ALTER COLUMN notes DROP NOT NULL");
            }
            // SQLite: leave as-is (column already accepts NULL via no NOT NULL constraint)
        }

        // `processed_by` (admin user id)
        if (!Schema::hasColumn('withdrawals', 'processed_by')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `withdrawals`
                    ADD COLUMN `processed_by` BIGINT UNSIGNED NULL
                    COMMENT 'Admin user id who approved/rejected this withdrawal'
                    AFTER `admin_note`");
            } else {
                DB::statement("ALTER TABLE withdrawals ADD COLUMN processed_by BIGINT NULL");
            }
        }

        // `processed_at` (timestamp)
        if (!Schema::hasColumn('withdrawals', 'processed_at')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `withdrawals`
                    ADD COLUMN `processed_at` TIMESTAMP NULL AFTER `processed_by`");
            } elseif ($driver === 'pgsql') {
                DB::statement("ALTER TABLE withdrawals ADD COLUMN processed_at TIMESTAMP NULL");
            } else {
                // sqlite
                DB::statement("ALTER TABLE withdrawals ADD COLUMN processed_at TIMESTAMP NULL");
            }
        }

        // Useful composite index for the admin queue
        try {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("CREATE INDEX `withdrawals_method_status_created_idx`
                    ON `withdrawals` (`method`, `status`, `created_at`)");
            } elseif ($driver === 'sqlite') {
                DB::statement("CREATE INDEX IF NOT EXISTS withdrawals_method_status_created_idx
                    ON withdrawals (method, status, created_at)");
            } elseif ($driver === 'pgsql') {
                DB::statement("CREATE INDEX IF NOT EXISTS withdrawals_method_status_created_idx
                    ON withdrawals (method, status, created_at)");
            }
        } catch (\Throwable $e) {
            // Index may already exist — ignore.
        }
    }

    public function down()
    {
        $driver = DB::connection()->getDriverName();

        try {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("DROP INDEX `withdrawals_method_status_created_idx` ON `withdrawals`");
            } else {
                DB::statement("DROP INDEX IF EXISTS withdrawals_method_status_created_idx");
            }
        } catch (\Throwable $e) {}

        Schema::table('withdrawals', function (Blueprint $table) {
            if (Schema::hasColumn('withdrawals', 'method'))      $table->dropColumn('method');
            if (Schema::hasColumn('withdrawals', 'network'))     $table->dropColumn('network');
            if (Schema::hasColumn('withdrawals', 'processed_by')) $table->dropColumn('processed_by');
            if (Schema::hasColumn('withdrawals', 'processed_at')) $table->dropColumn('processed_at');
        });
    }
}
