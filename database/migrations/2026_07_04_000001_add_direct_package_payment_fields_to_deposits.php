<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDirectPackagePaymentFieldsToDeposits extends Migration
{
    public function up()
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'payment_context')) {
                $table->string('payment_context', 50)->nullable()->after('deposit_method');
            }
            if (!Schema::hasColumn('deposits', 'package_type')) {
                $table->string('package_type', 30)->nullable()->after('payment_context');
            }
            if (!Schema::hasColumn('deposits', 'package_id')) {
                $table->unsignedBigInteger('package_id')->nullable()->after('package_type');
            }
            if (!Schema::hasColumn('deposits', 'package_name')) {
                $table->string('package_name', 150)->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('deposits', 'activated_payment_id')) {
                $table->unsignedBigInteger('activated_payment_id')->nullable()->after('package_name');
            }
            if (!Schema::hasColumn('deposits', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('activated_payment_id');
            }
            if (!Schema::hasColumn('deposits', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('activated_at');
            }
        });

        $this->safeIndex('deposits', 'deposits_payment_context_status_idx', 'CREATE INDEX deposits_payment_context_status_idx ON deposits (payment_context, status)');
        $this->safeIndex('deposits', 'deposits_package_intent_idx', 'CREATE INDEX deposits_package_intent_idx ON deposits (user_id, payment_context, package_type, package_id, status)');

        // The production SQL dump already allows "used". Keep migrations aligned
        // for fresh installs that still have the older enum definition.
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement("ALTER TABLE deposits MODIFY status enum('pending','approved','rejected','under-review','cancelled','used') NOT NULL DEFAULT 'pending'");
            } catch (\Throwable $e) {
                // Non-critical: some environments use a different column type or
                // already have this exact enum.
            }
        }
    }

    public function down()
    {
        Schema::table('deposits', function (Blueprint $table) {
            foreach (['payment_context', 'package_type', 'package_id', 'package_name', 'activated_payment_id', 'activated_at', 'expires_at'] as $column) {
                if (Schema::hasColumn('deposits', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function safeIndex(string $table, string $name, string $sql): void
    {
        try {
            DB::statement($sql);
        } catch (\Throwable $e) {
            // Index probably already exists or the DB driver does not support the syntax.
        }
    }
}
