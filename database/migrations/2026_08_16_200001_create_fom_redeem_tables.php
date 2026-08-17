<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Volume-Point → USDT redemption:
 *  - fom_redeem_options: admin-configurable boxes (usdt_amount / points_required)
 *  - fom_redeem_logs: who redeemed what (admin audit list)
 */
class CreateFomRedeemTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('fom_redeem_options')) {
            Schema::create('fom_redeem_options', function (Blueprint $table) {
                $table->id();
                $table->decimal('usdt_amount', 20, 2);
                $table->decimal('points_required', 20, 2);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fom_redeem_logs')) {
            Schema::create('fom_redeem_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('option_id')->nullable();
                $table->decimal('points_spent', 20, 2);
                $table->decimal('usdt_received', 20, 2);
                $table->dateTime('redeemed_at');
                $table->timestamps();
                $table->index(['user_id'], 'frl_user_idx');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('fom_redeem_logs');
        Schema::dropIfExists('fom_redeem_options');
    }
}
