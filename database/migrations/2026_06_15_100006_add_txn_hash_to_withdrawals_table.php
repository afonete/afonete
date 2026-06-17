<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add txn_hash + processing state columns to withdrawals.
 * Lets admin record the on-chain hash when they manually send USDT,
 * and supports the proper pending → processing → completed flow.
 */
class AddTxnHashToWithdrawalsTable extends Migration
{
    public function up()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawals', 'txn_hash')) {
                $table->string('txn_hash')->nullable()->after('plisio_txn_id')
                      ->comment('On-chain tx hash admin records after manually sending USDT');
            }
            if (!Schema::hasColumn('withdrawals', 'gas_fee')) {
                $table->decimal('gas_fee', 12, 6)->nullable()->after('amount')
                      ->comment('Plisio gas fee deducted from amount');
            }
            if (!Schema::hasColumn('withdrawals', 'net_amount')) {
                $table->decimal('net_amount', 12, 6)->nullable()->after('amount')
                      ->comment('Amount actually received by user after gas');
            }
        });
    }

    public function down()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['txn_hash','gas_fee','net_amount']);
        });
    }
}
