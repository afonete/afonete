<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RebuildTokenSystem extends Migration
{
    public function up()
    {
        // ── 1. Extend token_settings with all 5 price types + coin_value ──
        Schema::table('token_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('token_settings', 'uvp_price')) {
                $table->decimal('uvp_price', 12, 6)->default(0.002500)
                      ->comment('UVP package token price: investment / uvp_price = LOCKED tokens granted on purchase');
            }
            if (!Schema::hasColumn('token_settings', 'renewal_price')) {
                $table->decimal('renewal_price', 12, 6)->default(0.002500)
                      ->comment('Token price used when renewing every 30 days (trading voucher / renewal_price = AVAILABLE tokens)');
            }
            if (!Schema::hasColumn('token_settings', 'swap_price')) {
                $table->decimal('swap_price', 12, 6)->default(0.002500)
                      ->comment('Token price used when user swaps LOCKED tokens → CASHOUT internally');
            }
            if (!Schema::hasColumn('token_settings', 'trading_price')) {
                $table->decimal('trading_price', 12, 6)->default(0.002500)
                      ->comment('Buy/sell token price — reserved for future trading module');
            }
            if (!Schema::hasColumn('token_settings', 'package_price')) {
                $table->decimal('package_price', 12, 6)->default(0.002500)
                      ->comment('Price to buy a referral package — reserved for future use');
            }
            if (!Schema::hasColumn('token_settings', 'coin_value')) {
                $table->decimal('coin_value', 12, 6)->default(0.002000)
                      ->comment('Display value per token in USD. NOT used in swap math — swap uses swap_price. Shown on the dashboard so users understand what their tokens are "worth".');
            }
        });

        // ── 2. Sync existing token_price → uvp_price if already set ──
        DB::table('token_settings')->update([
            'uvp_price'     => DB::raw('token_price'),
            'renewal_price' => DB::raw('token_price'),
            'swap_price'    => DB::raw('token_price'),
            'trading_price' => DB::raw('token_price'),
            'package_price' => DB::raw('token_price'),
        ]);

        // ── 3. Create token_withdrawals table ──
        if (!Schema::hasTable('token_withdrawals')) {
            Schema::create('token_withdrawals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->decimal('token_amount', 16, 4)->comment('Number of tokens to withdraw');
                $table->decimal('coin_value_at_request', 12, 6)->comment('Snapshot of coin_value at time of request');
                $table->string('wallet_address')->comment('User-provided FONE/crypto wallet address');
                $table->string('transaction_no')->unique();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('admin_note')->nullable();
                $table->string('source_wallet')->default('FREE_TOKEN')->comment('Which wallet the withdrawal came from: FREE_TOKEN or LOCKED_TOKEN');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // ── 4. Rename acc_type values in chart_accounts ──
        // Final token system:
        //   OLD FREE_TOKEN   → LOCKED_TOKEN  (full investment tokens, locked during package)
        //   OLD LOCKED_TOKEN → GAS_FEE       (20% charges, admin-only)
        //   FREE_TOKEN (new) → user-usable tokens released after package expires
        //   AVAILABLE_TOKEN  → tokens earned each 30-day renewal (unchanged)
        //
        // We use a temp name to avoid collision during rename:
        DB::statement("UPDATE chart_accounts SET acc_type = 'GAS_FEE' WHERE acc_type = 'LOCKED_TOKEN'");
        DB::statement("UPDATE chart_accounts SET acc_type = 'LOCKED_TOKEN' WHERE acc_type = 'FREE_TOKEN'");
        // FREE_TOKEN rows now = 0 (new bucket, users start empty; fills when user transfers from AVAILABLE_TOKEN)
    }

    public function down()
    {
        // Reverse renames
        DB::statement("UPDATE chart_accounts SET acc_type = 'FREE_TOKEN' WHERE acc_type = 'LOCKED_TOKEN'");
        DB::statement("UPDATE chart_accounts SET acc_type = 'LOCKED_TOKEN' WHERE acc_type = 'GAS_FEE'");

        Schema::dropIfExists('token_withdrawals');

        Schema::table('token_settings', function (Blueprint $table) {
            $table->dropColumn(['uvp_price','renewal_price','swap_price','trading_price','package_price','coin_value']);
        });
    }
}
