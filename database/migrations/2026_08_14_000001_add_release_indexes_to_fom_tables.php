<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Adds composite indexes used by the FOM release queries that run on every
 * dashboard/home/finance page load:
 *
 *   WHERE user_id = ? AND status = 'pending' AND release_date <= NOW()
 *
 * Without these, both tables are full-scanned per request.
 */
class AddReleaseIndexesToFomTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('fom_token_installments') && !$this->indexExists('fom_token_installments', 'fti_user_status_release_idx')) {
            Schema::table('fom_token_installments', function (Blueprint $table) {
                $table->index(['user_id', 'status', 'release_date'], 'fti_user_status_release_idx');
            });
        }

        if (Schema::hasTable('fom_token_stakings') && !$this->indexExists('fom_token_stakings', 'fts_user_status_release_idx')) {
            Schema::table('fom_token_stakings', function (Blueprint $table) {
                $table->index(['user_id', 'status', 'release_date'], 'fts_user_status_release_idx');
            });
        }

        // ChartAccount wallet rows are looked up (and now row-locked) by
        // (user_id, acc_type) on every balance mutation.
        if (Schema::hasTable('chart_accounts') && !$this->indexExists('chart_accounts', 'ca_user_acc_type_idx')) {
            Schema::table('chart_accounts', function (Blueprint $table) {
                $table->index(['user_id', 'acc_type'], 'ca_user_acc_type_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('fom_token_installments') && $this->indexExists('fom_token_installments', 'fti_user_status_release_idx')) {
            Schema::table('fom_token_installments', function (Blueprint $table) {
                $table->dropIndex('fti_user_status_release_idx');
            });
        }

        if (Schema::hasTable('fom_token_stakings') && $this->indexExists('fom_token_stakings', 'fts_user_status_release_idx')) {
            Schema::table('fom_token_stakings', function (Blueprint $table) {
                $table->dropIndex('fts_user_status_release_idx');
            });
        }

        if (Schema::hasTable('chart_accounts') && $this->indexExists('chart_accounts', 'ca_user_acc_type_idx')) {
            Schema::table('chart_accounts', function (Blueprint $table) {
                $table->dropIndex('ca_user_acc_type_idx');
            });
        }
    }

    /**
     * Driver-agnostic index existence check (works on MySQL & SQLite).
     */
    protected function indexExists(string $table, string $index): bool
    {
        try {
            $driver = Schema::getConnection()->getDriverName();

            if ($driver === 'mysql') {
                $rows = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]);
                return count($rows) > 0;
            }

            if ($driver === 'sqlite') {
                $rows = DB::select("SELECT name FROM sqlite_master WHERE type = 'index' AND name = ?", [$index]);
                return count($rows) > 0;
            }
        } catch (\Throwable $e) {
            // Fall through — attempting to create a duplicate index will fail
            // loudly, which is better than silently skipping.
        }

        return false;
    }
}
