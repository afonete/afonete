<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
            
            Schema::table('positions', function (Blueprint $table) {
    $table->string('cashout')->default('not allowed');
    $table->string('unique_task')->default('no unique task');
    $table->string('general_task')->default('no general task');
    
    $table->string('duration')->default('not set');
    $table->string('withdraw')->default('0');
    $table->string('daily_bonus')->default('0');
});
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('positions', function (Blueprint $table) {
            //
        });
    }
}
