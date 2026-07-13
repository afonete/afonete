<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentageRangeToAdventuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('Adventures') && !Schema::hasColumn('Adventures', 'percentage_range')) {
            Schema::table('Adventures', function (Blueprint $table) {
                $table->string('percentage_range')->nullable()->after('percentage')
                      ->comment('Optional display range for package interest percentage on purchase UI');
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
        if (Schema::hasColumn('Adventures', 'percentage_range')) {
            Schema::table('Adventures', function (Blueprint $table) {
                $table->dropColumn('percentage_range');
            });
        }
    }
}
