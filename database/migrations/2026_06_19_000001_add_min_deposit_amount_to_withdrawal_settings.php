<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add platform-wide configurable deposit minimum to withdrawal_settings.
 *
 * Note: this table is reused as the "platform financial limits" bucket — it
 * already holds min_amount, max_per_transaction, daily_limit, monthly_limit.
 * Keeping deposit min alongside withdrawal limits keeps all admin-configurable
 * monetary thresholds in one place.
 */
class AddMinDepositAmountToWithdrawalSettings extends Migration
{
    public function up()
    {
        Schema::table('withdrawal_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawal_settings', 'min_deposit_amount')) {
                $table->decimal('min_deposit_amount', 10, 2)->nullable()->default(10)
                      ->after('min_amount')
                      ->comment('Minimum USD amount the user can deposit (both manual and Plisio auto). Admin-set.');
            }
        });
    }

    public function down()
    {
        Schema::table('withdrawal_settings', function (Blueprint $table) {
            $table->dropColumn('min_deposit_amount');
        });
    }
}
