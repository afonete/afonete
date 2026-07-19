<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamLeaderFeaturesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Team Leader Events & Zoom Meetings (Page 1 & 2)
        if (!Schema::hasTable('team_leader_events')) {
            Schema::create('team_leader_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('title');
                $table->string('type')->default('physical'); // physical or zoom
                $table->string('location')->nullable();
                $table->string('zoom_link')->nullable();
                $table->dateTime('event_time');
                $table->string('status')->default('pending'); // pending, approved, rejected
                
                // Proof fields (Page 2)
                $table->boolean('proof_submitted')->default(false);
                $table->text('proof_files')->nullable(); // json / comma-separated URLs or names
                $table->text('proof_notes')->nullable();
                $table->string('proof_status')->default('none'); // none, pending, approved, rejected
                
                $table->timestamps();
            });
        }

        // 2. Team Leader Ambassador Submissions (Page 3)
        if (!Schema::hasTable('team_leader_socials')) {
            Schema::create('team_leader_socials', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('platform'); // instagram, facebook, x, linkedin, etc.
                $table->string('profile_link');
                $table->integer('views_count')->default(0);
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->text('notes')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('team_leader_events');
        Schema::dropIfExists('team_leader_socials');
    }
}
