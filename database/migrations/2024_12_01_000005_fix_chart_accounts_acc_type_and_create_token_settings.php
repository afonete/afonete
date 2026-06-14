<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixChartAccountsAccTypeAndCreateTokenSettings extends Migration
{
    public function up()
    {
        // ── 1. Fix chart_accounts acc_type ──────────────────────────────────
        // The column may be an ENUM (causing "Data truncated" on new values)
        // or a plain VARCHAR. We convert it to VARCHAR(30) to support any value
        // without needing a migration every time a new type is added.
        DB::statement("ALTER TABLE chart_accounts MODIFY COLUMN acc_type VARCHAR(30) NOT NULL");

        // ── 2. token_settings table ─────────────────────────────────────────
        if (!Schema::hasTable('token_settings')) {
            Schema::create('token_settings', function (Blueprint $table) {
                $table->id();
                $table->decimal('token_price', 12, 6)->default(0.002500)
                      ->comment('Price per token in USD. Admin-set. Used for FREE_TOKEN, LOCKED_TOKEN on purchase and AVAILABLE_TOKEN on renewal.');
                $table->string('currency', 10)->default('USD');
                $table->string('token_symbol', 20)->default('FONE')
                      ->comment('Display symbol shown on dashboard e.g. FONE');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });

            // Seed the default row so the system always has one row to read
            DB::table('token_settings')->insert([
                'token_price'  => 0.002500,
                'currency'     => 'USD',
                'token_symbol' => 'FONE',
                'notes'        => 'Initial default token price',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // ── 3. Drop token_price from fcspackages if it was added ─────────────
        if (Schema::hasTable('fcspackages') && Schema::hasColumn('fcspackages', 'token_price')) {
            Schema::table('fcspackages', function (Blueprint $table) {
                $table->dropColumn('token_price');
            });
        }
    }

    public function down()
    {
        // Restore ENUM (original values only — extend as needed)
        DB::statement("ALTER TABLE chart_accounts MODIFY COLUMN acc_type ENUM('TRADING','CASHOUT','COMMISSION','PAID_ADS') NOT NULL");
        Schema::dropIfExists('token_settings');
    }
}
