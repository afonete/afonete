<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('activations')) {
            Schema::table('activations', function (Blueprint $table) {
                if (!Schema::hasColumn('activations', 'is_auto_code')) {
                    $table->boolean('is_auto_code')->default(false)->after('stutus')
                          ->comment('True = TM Auto Activation code generated via dashboard (usable by anyone). False = Tied exclusively to specific team leader email.');
                }
                if (!Schema::hasColumn('activations', 'credit_conditions')) {
                    $table->text('credit_conditions')->nullable()->after('is_auto_code')
                          ->comment('JSON encoded Super Leader credit conditions (credit_amount, sales_turnover_target, turnover_target_percent, turnover_reward_percent, auto_withdrawal_percent)');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('activations')) {
            Schema::table('activations', function (Blueprint $table) {
                if (Schema::hasColumn('activations', 'is_auto_code')) {
                    $table->dropColumn('is_auto_code');
                }
                if (Schema::hasColumn('activations', 'credit_conditions')) {
                    $table->dropColumn('credit_conditions');
                }
            });
        }
    }
};
