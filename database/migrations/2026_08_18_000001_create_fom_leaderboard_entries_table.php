<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFomLeaderboardEntriesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('fom_leaderboard_entries')) {
            return;
        }

        Schema::create('fom_leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->date('week_start')->index();
            $table->unsignedInteger('position');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('display_name')->nullable();
            $table->decimal('total_earned', 20, 4)->default(0);
            $table->string('earned_from', 30)->default('UVP');
            $table->boolean('is_manual')->default(false);
            $table->timestamps();
            $table->unique(['week_start', 'position']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fom_leaderboard_entries');
    }
}
