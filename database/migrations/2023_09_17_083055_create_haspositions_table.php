<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHaspositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('haspositions', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('unique_task')->unique()->defaut('no unique task');
            $table->string('general_task')->defaut('no genaral task');
            $table->string('cashout')->defaut('not allowed');
            $table->string('duration')->defaut('not seted');
            $table->string('withdraw')->defaut(0);
            $table->string('daily_bonus')->defaut(0);
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('haspositions');
    }
}
