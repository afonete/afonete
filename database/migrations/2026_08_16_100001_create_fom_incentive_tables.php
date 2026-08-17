<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FOM Licence Miner incentive:
 *  - fom_incentive_tiers: admin-configurable achievement/duration/bonus
 *  - fom_incentive_awards: users who met a tier (admin achievers list)
 */
class CreateFomIncentiveTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('fom_incentive_tiers')) {
            Schema::create('fom_incentive_tiers', function (Blueprint $table) {
                $table->id();
                $table->decimal('achievement', 20, 2);
                $table->integer('duration_days');
                $table->decimal('bonus', 20, 2);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fom_incentive_awards')) {
            Schema::create('fom_incentive_awards', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('tier_id');
                $table->decimal('achievement', 20, 2);
                $table->integer('duration_days');
                $table->decimal('bonus', 20, 2);
                $table->decimal('achieved_amount', 20, 2)->default(0);
                $table->dateTime('window_start');
                $table->dateTime('window_end');
                $table->dateTime('awarded_at');
                $table->unsignedBigInteger('anchor_payment_id')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'tier_id', 'anchor_payment_id'], 'fia_user_tier_anchor_unique');
                $table->index(['user_id'], 'fia_user_idx');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('fom_incentive_awards');
        Schema::dropIfExists('fom_incentive_tiers');
    }
}
