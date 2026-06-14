<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageRenewalsTable extends Migration
{
    public function up()
    {
        // NOTE: we do NOT touch fcspackages here.
        // Token price is managed in the token_settings table (migration 000005).

        Schema::create('package_renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_id')
                  ->comment('The original package payment being renewed');
            $table->decimal('amount_paid', 10, 2)
                  ->comment('Amount deducted from Trading Voucher balance');
            $table->decimal('token_price_at_renewal', 12, 6)
                  ->comment('Snapshot of token price at time of renewal (from token_settings)');
            $table->decimal('tokens_received', 18, 4)
                  ->comment('AVAILABLE tokens credited = amount_paid / token_price_at_renewal');
            $table->integer('renewal_number')->default(1)
                  ->comment('Which renewal cycle (1 = first 30-day window, 2 = second, etc.)');
            $table->integer('max_renewals')->default(3)
                  ->comment('Total renewals for this package (derived from adventures.duration)');
            $table->date('renewed_at')
                  ->comment('Date this renewal was processed');
            $table->date('next_renewal_due')->nullable()
                  ->comment('Next renewal due date (null if this was the final renewal)');
            $table->string('transaction_no')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_renewals');
    }
}
