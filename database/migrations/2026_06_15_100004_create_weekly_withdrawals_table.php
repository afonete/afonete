<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Weekly referral-bonus withdrawal requests.
 *
 * Per spec: user can only withdraw referral bonus on Monday.
 *   - week_start: Monday date the bonus belongs to
 *   - amount: sum of all referral_bonuses for that user/week
 *   - status: pending → approved → paid / rejected
 *   - transaction_no: reference shown to user & admin
 */
class CreateWeeklyWithdrawalsTable extends Migration
{
    public function up()
    {
        Schema::create('weekly_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('week_start')->comment('Monday date');
            $table->decimal('amount', 14, 4)->comment('Total USD being withdrawn this week');
            $table->string('transaction_no')->unique();
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->string('payment_method')->default('manual')
                  ->comment('manual | plisio | bank');
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'week_start']);
            $table->index(['status', 'week_start']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('weekly_withdrawals');
    }
}
