<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFomRanksTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('fom_ranks')) {
            Schema::create('fom_ranks', function (Blueprint $table) {
                $table->id();
                $table->string('group_name', 60);
                $table->string('name', 60)->unique();
                $table->unsignedInteger('level')->unique();
                $table->decimal('vb_required', 20, 4)->default(0);
                $table->decimal('team_turnover', 20, 2)->default(0);
                $table->decimal('personal_turnover', 20, 2)->default(0);
                $table->string('criteria_label')->nullable();
                $table->text('criteria_json')->nullable();
                $table->string('licence_min', 30)->default('BASIC');
                $table->decimal('reward', 20, 2)->default(0);
                $table->string('extra_bonuses')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fom_user_ranks')) {
            Schema::create('fom_user_ranks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('fom_rank_id');
                $table->string('rank_name', 60);
                $table->unsignedInteger('rank_level');
                $table->string('status', 20)->default('pending');
                $table->timestamp('detected_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->decimal('reward_paid', 20, 2)->default(0);
                $table->text('criteria_snapshot')->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'fom_rank_id']);
            });
        }

        // Seed the 17-rank ladder
        \App\Models\FomRank::ensureTableAndData();
    }

    public function down()
    {
        Schema::dropIfExists('fom_user_ranks');
        Schema::dropIfExists('fom_ranks');
    }
}
