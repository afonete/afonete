<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStakingYieldsToTokenSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('token_settings')) {
            Schema::table('token_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('token_settings', 'staking_yield_1_year')) {
                    $table->decimal('staking_yield_1_year', 8, 2)->default(10.00);
                }
                if (!Schema::hasColumn('token_settings', 'staking_yield_2_year')) {
                    $table->decimal('staking_yield_2_year', 8, 2)->default(25.00);
                }
                if (!Schema::hasColumn('token_settings', 'staking_yield_3_year')) {
                    $table->decimal('staking_yield_3_year', 8, 2)->default(45.00);
                }
                if (!Schema::hasColumn('token_settings', 'staking_yield_4_year')) {
                    $table->decimal('staking_yield_4_year', 8, 2)->default(70.00);
                }
                if (!Schema::hasColumn('token_settings', 'staking_yield_5_year')) {
                    $table->decimal('staking_yield_5_year', 8, 2)->default(100.00);
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
        if (Schema::hasTable('token_settings')) {
            Schema::table('token_settings', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('token_settings', 'staking_yield_1_year')) $cols[] = 'staking_yield_1_year';
                if (Schema::hasColumn('token_settings', 'staking_yield_2_year')) $cols[] = 'staking_yield_2_year';
                if (Schema::hasColumn('token_settings', 'staking_yield_3_year')) $cols[] = 'staking_yield_3_year';
                if (Schema::hasColumn('token_settings', 'staking_yield_4_year')) $cols[] = 'staking_yield_4_year';
                if (Schema::hasColumn('token_settings', 'staking_yield_5_year')) $cols[] = 'staking_yield_5_year';
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
}
