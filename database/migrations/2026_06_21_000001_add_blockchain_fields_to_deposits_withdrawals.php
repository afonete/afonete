<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlockchainFieldsToDepositsWithdrawals extends Migration
{
    public function up()
    {
        // Deposits
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'blockchain_tx_hash')) {
                $table->string('blockchain_tx_hash')->nullable()->after('transaction_id')
                      ->comment('On-chain TX hash for direct blockchain deposits');
            }
        });

        // Withdrawals
        Schema::table('withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawals', 'blockchain_tx_hash')) {
                $table->string('blockchain_tx_hash')->nullable()->after('txn_hash')
                      ->comment('On-chain TX hash for direct blockchain withdrawals');
            }
            if (!Schema::hasColumn('withdrawals', 'network')) {
                $table->string('network')->nullable()->after('currency');
            }
        });
    }

    public function down()
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (Schema::hasColumn('deposits', 'blockchain_tx_hash')) {
                $table->dropColumn('blockchain_tx_hash');
            }
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            if (Schema::hasColumn('withdrawals', 'blockchain_tx_hash')) {
                $table->dropColumn('blockchain_tx_hash');
            }
            if (Schema::hasColumn('withdrawals', 'network')) {
                $table->dropColumn('network');
            }
        });
    }
}