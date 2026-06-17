<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Referral bonus ledger.
 *
 * One row per commission credit:
 *   - user_id          : who receives the bonus
 *   - source_user_id   : whose package purchase generated it
 *   - source_payment_id: the original payment row
 *   - level            : 1=direct (10%), 2=indirect (1%), 3=3rd-level (0.5%)
 *   - percentage       : 10 / 1 / 0.5 (snapshot at the time of credit)
 *   - source_amount    : package amount (USD) that triggered this bonus
 *   - bonus_amount     : commission credited (USD)
 *   - week_start       : Monday date when this row becomes withdrawable
 *   - status           : pending → withdrawable → withdrawn / expired
 *   - withdrawn_at     : timestamp
 *   - source           : 'referral' | 'rank_reward' | 'associate_manager'
 *   - notes            : optional (rank name for rank rewards, etc.)
 */
class CreateReferralBonusesTable extends Migration
{
    public function up()
    {
        Schema::create('referral_bonuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Who RECEIVES the bonus');
            $table->unsignedBigInteger('source_user_id')->comment('Whose purchase generated it');
            $table->unsignedBigInteger('source_payment_id')->nullable();
            $table->unsignedTinyInteger('level')->comment('1=direct, 2=indirect, 3=3rd-level');
            $table->decimal('percentage', 5, 2)->comment('10.00 / 1.00 / 0.50');
            $table->decimal('source_amount', 12, 2)->comment('Package amount in USD');
            $table->decimal('bonus_amount', 12, 4)->comment('Commission credited in USD');
            $table->date('week_start')->comment('Monday date when this becomes withdrawable');
            $table->enum('status', ['pending', 'withdrawable', 'withdrawn', 'expired', 'reversed'])
                  ->default('pending');
            $table->timestamp('withdrawn_at')->nullable();
            $table->string('source', 30)->default('referral')
                  ->comment('referral | rank_reward | associate_manager');
            $table->string('source_ref', 100)->nullable()
                  ->comment('e.g. rank slug or user_rank id');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'week_start']);
            $table->index(['status', 'week_start']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_bonuses');
    }
}
