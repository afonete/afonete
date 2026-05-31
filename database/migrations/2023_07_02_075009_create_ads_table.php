<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->string('banner')->nullable();
            $table->string('tittle');
            $table->text('description');
            $table->string('targeted_views');
            $table->string('reached_views')->nullable();
            $table->string('targeted_duration');
            $table->string('location');
            $table->string('user');
            $table->string('question');
            $table->string('answer');
            $table->string('status');
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
        Schema::dropIfExists('ads');
    }
}
