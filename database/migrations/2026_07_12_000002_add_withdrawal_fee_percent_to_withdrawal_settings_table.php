<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWithdrawalFeePercentToWithdrawalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('withdrawal_settings') && !Schema::hasColumn('withdrawal_settings', 'withdrawal_fee_percent')) {
            Schema::table('withdrawal_settings', function (Blueprint $table) {
                $table->decimal('withdrawal_fee_percent', 5, 2)->default(0.00)->after('allow_free_dashboard_access')
                      ->comment('Percentage fee charged on user withdrawals');
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
        if (Schema::hasColumn('withdrawal_settings', 'withdrawal_fee_percent')) {
            Schema::table('withdrawal_settings', function (Blueprint $table) {
                $table->dropColumn('withdrawal_fee_percent');
            });
        }
    }
}
