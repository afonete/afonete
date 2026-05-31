<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
           $table->id();
           
            $table->string('video');
            $table->string('tittle');
          
            $table->string('targeted_views');
            $table->string('reached_views')->nullable();
            $table->string('targeted_duration');
            $table->string('location');
            $table->string('user');
            $table->string('question');
            $table->string('answer');$table->string('time');
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
        Schema::dropIfExists('videos');
    }
}
