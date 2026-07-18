<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplyFieldsToTokenSettingsTable extends Migration
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
                if (!Schema::hasColumn('token_settings', 'initial_supply')) {
                    $table->decimal('initial_supply', 24, 4)->default(120000000000)->after('notes')
                          ->comment('Configurable starting total supply (default 120 Billion)');
                }
                if (!Schema::hasColumn('token_settings', 'initial_liquidity')) {
                    $table->decimal('initial_liquidity', 24, 4)->default(40000000000)->after('initial_supply')
                          ->comment('Configurable starting liquidity pool (default 40 Billion)');
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
                if (Schema::hasColumn('token_settings', 'initial_supply')) {
                    $table->dropColumn('initial_supply');
                }
                if (Schema::hasColumn('token_settings', 'initial_liquidity')) {
                    $table->dropColumn('initial_liquidity');
                }
            });
        }
    }
}
