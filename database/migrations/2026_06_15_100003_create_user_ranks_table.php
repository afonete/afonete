<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * User rank applications.
 *
 *   - status: pending → approved / rejected
 *   - detected_at: when the system noticed the user met the criteria
 *   - reviewed_by / reviewed_at: admin action
 *   - congratulation_image: file path uploaded by admin on approval
 *   - reward_amount: snapshot of the reward at the time of approval (USD)
 *   - referral_bonus_id: links to the referral_bonuses row created on approval
 */
class CreateUserRanksTable extends Migration
{
    public function up()
    {
        Schema::create('user_ranks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rank_id');
            $table->string('rank_name');
            $table->string('rank_slug');
            $table->unsignedTinyInteger('rank_level');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('detected_at')->nullable()
                  ->comment('When the system first detected user met the criteria');
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();

            $table->string('congratulation_image')->nullable()
                  ->comment('Path to admin-uploaded picture (e.g. congratulation card)');
            $table->text('admin_notes')->nullable();
            $table->decimal('reward_amount', 14, 2)->default(0)
                  ->comment('Reward USD amount at time of approval');
            $table->unsignedBigInteger('referral_bonus_id')->nullable()
                  ->comment('referral_bonuses.id created on approval');
            $table->json('criteria_snapshot')->nullable()
                  ->comment('Snapshot of computed criteria values when detected');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rank_id')->references('id')->on('rank_settings')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'rank_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_ranks');
    }
}
