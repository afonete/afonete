<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdventuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('Adventures', function (Blueprint $table) {
            $table->id();
            $table->string("name", 200)->default('UVP');
            $table->string("plan", 200)->comment("plan name like VENTURE LIGHT");
            $table->decimal("min_amount", 8, 2);
            $table->decimal("max_amount", 8, 2);
            $table->float("percentage");
            $table->integer("duration")->comment("in days");
            $table->string("total_return")->comment("total returns ex: 130%");
            $table->string("currency", 3)->comment("currency code like USD");
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
        Schema::dropIfExists('Adventures');
    }
}
