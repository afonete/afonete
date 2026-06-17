<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Withdrawal limits (admin-configurable).
 * Single-row settings table seeded with sane defaults.
 */
class CreateWithdrawalSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('withdrawal_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_amount', 10, 2)->default(10)->comment('Per spec: min $10');
            $table->decimal('max_per_transaction', 14, 2)->default(10000);
            $table->decimal('daily_limit', 14, 2)->default(20000)->nullable();
            $table->decimal('monthly_limit', 14, 2)->default(100000)->nullable();
            $table->boolean('require_admin_approval')->default(true)
                  ->comment('When false, instant Plisio withdrawals skip approval queue');
            $table->decimal('default_trc20_min_length', 3, 0)->default(34);
            $table->boolean('validate_trc20_format')->default(true);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        // Seed default row
        DB::table('withdrawal_settings')->insert([
            'min_amount'               => 10,
            'max_per_transaction'      => 10000,
            'daily_limit'              => 20000,
            'monthly_limit'            => 100000,
            'require_admin_approval'   => true,
            'default_trc20_min_length' => 34,
            'validate_trc20_format'    => true,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('withdrawal_settings');
    }
}
