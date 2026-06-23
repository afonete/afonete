<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockchainDepositAddressesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('blockchain_deposit_addresses')) {
            return;
        }

        Schema::create('blockchain_deposit_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency', 20)->default('USDT');
            $table->string('network', 30)->default('TRC-20');
            $table->string('address', 80)->unique();
            $table->text('encrypted_private_key')->nullable();
            $table->string('derivation_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_scanned_at')->nullable();
            $table->string('last_tx_hash')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'currency', 'network'], 'bda_user_currency_network_unique');
            $table->index(['currency', 'network', 'is_active'], 'bda_currency_network_active_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blockchain_deposit_addresses');
    }
}
