<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Company deposit wallets (admin-editable per network).
 *
 * Replaces the hardcoded placeholder "TYourCompanyWalletAddressHere"
 * in the user deposit form. Admin sets these at /admin/settings/wallets.
 */
class CreateDepositWalletsTable extends Migration
{
    public function up()
    {
        Schema::create('deposit_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('network', 20)->unique()->comment('TRC-20, ERC-20, BEP-20');
            $table->string('label')->comment('Display label e.g. "USDT TRC-20 (Tron)"');
            $table->string('wallet_address');
            $table->string('currency', 10)->default('USDT');
            $table->decimal('min_amount', 10, 2)->default(10);
            $table->decimal('max_amount', 14, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Seed the 3 standard USDT networks
        DB::table('deposit_wallets')->insert([
            [
                'network' => 'TRC-20',
                'label'   => 'USDT TRC-20 (Tron) — Recommended',
                'wallet_address' => env('DEPOSIT_WALLET_TRC20', 'TYourCompanyWalletAddressHere'),
                'currency' => 'USDT',
                'min_amount' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'network' => 'ERC-20',
                'label'   => 'USDT ERC-20 (Ethereum)',
                'wallet_address' => env('DEPOSIT_WALLET_ERC20', '0xYourCompanyEthWalletAddressHere'),
                'currency' => 'USDT',
                'min_amount' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'network' => 'BEP-20',
                'label'   => 'USDT BEP-20 (BSC)',
                'wallet_address' => env('DEPOSIT_WALLET_BEP20', '0xYourCompanyBscWalletAddressHere'),
                'currency' => 'USDT',
                'min_amount' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('deposit_wallets');
    }
}
