<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Token Savings: users can move tokens from AVAILABLE_TOKEN into a
     * SAVING_TOKEN bucket where they are locked for 6 months. After 6
     * months the user can move them back to AVAILABLE_TOKEN and becomes
     * eligible for a Credit (Loan).
     */
    public function up(): void
    {
        // Savings bucket on ChartAccount (shared with LOCKED_TOKEN / AVAILABLE_TOKEN).
        // We don't strictly need a migration to insert a new acc_type value
        // since acc_type is a string column, but the table tracks the
        // individual savings deposits.
        if (!Schema::hasTable('token_savings')) {
            Schema::create('token_savings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedDecimal('amount', 18, 4)->default(0);
                $table->date('start_date');
                $table->date('mature_date');   // start_date + 6 months
                $table->date('withdrawn_date')->nullable();
                $table->unsignedDecimal('withdrawn_amount', 18, 4)->default(0);
                $table->enum('status', ['active', 'matured', 'withdrawn'])->default('active');
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index(['user_id', 'mature_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('token_savings');
    }
};
