<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeAndNetAmountToWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('withdrawals')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                if (!Schema::hasColumn('withdrawals', 'fee_amount')) {
                    $table->decimal('fee_amount', 10, 2)->default(0.00)->after('amount')
                          ->comment('Calculated withdrawal fee based on percentage');
                }
                if (!Schema::hasColumn('withdrawals', 'net_amount')) {
                    $table->decimal('net_amount', 10, 2)->default(0.00)->after('fee_amount')
                          ->comment('Net payout amount after fee deduction');
                }
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
        if (Schema::hasTable('withdrawals')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                if (Schema::hasColumn('withdrawals', 'fee_amount')) $table->dropColumn('fee_amount');
                if (Schema::hasColumn('withdrawals', 'net_amount')) $table->dropColumn('net_amount');
            });
        }
    }
}
