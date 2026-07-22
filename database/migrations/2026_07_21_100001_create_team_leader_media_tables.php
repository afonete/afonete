<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ── Team Leader Videos ──
        if (!Schema::hasTable('team_leader_videos')) {
            Schema::create('team_leader_videos', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('video_url')->nullable()->comment('YouTube URL or storage path');
                $table->string('video_type')->default('youtube')->comment('youtube or mp4');
                $table->string('thumbnail')->nullable();
                $table->text('description')->nullable();
                $table->integer('duration_seconds')->default(60);
                $table->string('target_region')->nullable()->comment('e.g. Global, East Africa');
                $table->string('status')->default('approved')->comment('draft, approved, rejected');
                $table->text('rejected_reason')->nullable();
                $table->unsignedInteger('views_count')->default(0);
                $table->string('uploaded_by')->default('admin');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // ── Team Leader Banners ──
        if (!Schema::hasTable('team_leader_banners')) {
            Schema::create('team_leader_banners', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('image_path')->nullable();
                $table->text('description')->nullable();
                $table->string('landing_url')->nullable();
                $table->string('target_region')->nullable();
                $table->string('status')->default('approved')->comment('draft, approved, rejected');
                $table->text('rejected_reason')->nullable();
                $table->unsignedInteger('views_count')->default(0);
                $table->unsignedInteger('downloads_count')->default(0);
                $table->string('uploaded_by')->default('admin');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('team_leader_videos');
        Schema::dropIfExists('team_leader_banners');
    }
};
