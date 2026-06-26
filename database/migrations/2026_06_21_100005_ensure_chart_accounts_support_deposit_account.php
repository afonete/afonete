<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnsureChartAccountsSupportDepositAccount extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chart_accounts')) {
            return;
        }

        // Some older installs had acc_type as ENUM without DEPOSIT.
        // Use VARCHAR so DEPOSIT and future account buckets work safely.
        try {
            DB::statement("ALTER TABLE chart_accounts MODIFY COLUMN acc_type VARCHAR(30) NOT NULL");
        } catch (\Throwable $e) {
            // Ignore for non-MySQL drivers or already-compatible schemas.
        }
    }

    public function down()
    {
        // Do not revert to ENUM; that could break existing DEPOSIT rows.
    }
}
