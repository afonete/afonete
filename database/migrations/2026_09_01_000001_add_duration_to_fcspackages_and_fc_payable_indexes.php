<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * FC VIP packages are LIFETIME (no duration / no expiration).
     *
     * This migration:
     *   1. Makes payments.expiration_date and payments.duration nullable
     *      (required before we can null out legacy FC rows, since some older
     *      installs have these columns NOT NULL).
     *   2. Adds (if missing) a `default_token` column to fcspackages for admin UI.
     *   3. Drops any accidental `duration` column from fcspackages (FC has none).
     *   4. Adds the composite payments(user, category, status, is_expired) index
     *      (idempotent).
     *   5. Backfills LEGACY FC payment rows: clears expiration_date / duration
     *      and marks is_expired = 0 so FC VIP packages never expire.
     */
    public function up(): void
    {
        // ── Step 1: make expiration_date / duration nullable first ──────
        if (Schema::hasTable('payments')) {
            // Inspect current schema — only ALTER if still NOT NULL.
            // DB::select returns stdClass rows; MySQL's SHOW COLUMNS yields
            // uppercase property names (Field, Type, Null, Key, Default, Extra).
            $col = DB::select("SHOW COLUMNS FROM `payments` LIKE 'expiration_date'");
            if (!empty($col)) {
                $null = strtoupper((string) ($col[0]->Null ?? 'YES'));
                if ($null !== 'YES') {
                    DB::statement("ALTER TABLE `payments` MODIFY COLUMN `expiration_date` DATE NULL DEFAULT NULL");
                }
            }
            $col = DB::select("SHOW COLUMNS FROM `payments` LIKE 'duration'");
            if (!empty($col)) {
                $null = strtoupper((string) ($col[0]->Null ?? 'YES'));
                if ($null !== 'YES') {
                    DB::statement("ALTER TABLE `payments` MODIFY COLUMN `duration` INT NULL DEFAULT NULL");
                }
            }
        }

        if (Schema::hasTable('fcspackages')) {
            if (!Schema::hasColumn('fcspackages', 'default_token')) {
                Schema::table('fcspackages', function (Blueprint $table) {
                    $table->unsignedBigInteger('default_token')->nullable()->after('price');
                });
            }

            // Drop any duration column if a previous run accidentally added it —
            // FC VIP packages are lifetime.
            if (Schema::hasColumn('fcspackages', 'duration')) {
                Schema::table('fcspackages', function (Blueprint $table) {
                    $table->dropColumn('duration');
                });
            }
        }

        // ── Step 2: add composite index (idempotent) ──────────────────
        if (Schema::hasTable('payments')) {
            $conn = Schema::getConnection();
            $dbName = $conn->getDatabaseName();
            $exists = collect(DB::select("
                SELECT COUNT(*) AS c
                FROM information_schema.statistics
                WHERE table_schema = ?
                  AND table_name   = 'payments'
                  AND index_name   = 'payments_user_cat_status_exp_idx'
            ", [$dbName]))->first()->c;

            if (! $exists) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->index(['user', 'category', 'status', 'is_expired'], 'payments_user_cat_status_exp_idx');
                });
            }
        }

        // ── Step 3: self-heal legacy FC payment rows ───────────────────
        if (Schema::hasTable('payments')) {
            DB::statement("UPDATE payments SET is_expired = 0, expiration_date = NULL, duration = NULL WHERE UPPER(COALESCE(category,'')) = 'FC'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            $conn = Schema::getConnection();
            $dbName = $conn->getDatabaseName();
            $exists = collect(DB::select("
                SELECT COUNT(*) AS c
                FROM information_schema.statistics
                WHERE table_schema = ?
                  AND table_name   = 'payments'
                  AND index_name   = 'payments_user_cat_status_exp_idx'
            ", [$dbName]))->first()->c;

            if ($exists) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->dropIndex('payments_user_cat_status_exp_idx');
                });
            }
        }
    }
};
