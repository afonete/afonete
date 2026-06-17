<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * OPTIONAL migration: add payment_id to daily_incomes
 *
 * The existing daily_incomes table does NOT have a payment_id column,
 * which means the per-investment detail page can't strictly filter
 * daily income rows to a single package purchase. Run this migration
 * to enable precise per-investment daily income history.
 *
 * After running, the InvestmentController::show() will automatically
 * detect the column (via Schema::hasColumn) and use it for filtering.
 */
class AddPaymentIdToDailyIncomesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('daily_incomes') && !Schema::hasColumn('daily_incomes', 'payment_id')) {
            Schema::table('daily_incomes', function (Blueprint $table) {
                $table->unsignedBigInteger('payment_id')->nullable()->after('user_id')
                      ->comment('Links the daily income row to a specific package purchase');
                $table->index(['payment_id']);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('daily_incomes', 'payment_id')) {
            Schema::table('daily_incomes', function (Blueprint $table) {
                $table->dropIndex(['payment_id']);
                $table->dropColumn('payment_id');
            });
        }
    }
}
