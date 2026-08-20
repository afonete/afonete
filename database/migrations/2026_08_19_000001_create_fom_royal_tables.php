<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFomRoyalTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('fom_royal_tiers')) {
            Schema::create('fom_royal_tiers', function (Blueprint $table) {
                $table->id();
                $table->string('name', 20)->unique();
                $table->unsignedInteger('level')->unique();
                $table->text('sponsors_json');
                $table->unsignedInteger('duration_days');
                $table->decimal('vb_earn_required', 20, 2)->default(0);
                $table->decimal('prize_pool_usd', 20, 2)->default(0);
                $table->string('promo_hold_package', 30);
                $table->string('promo_grant_package', 30);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fom_royal_awards')) {
            Schema::create('fom_royal_awards', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('tier_id');
                $table->string('tier_name', 20);
                $table->string('status', 20)->default('pending');
                $table->timestamp('window_start')->nullable();
                $table->timestamp('window_end')->nullable();
                $table->timestamp('detected_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->decimal('focoin_price', 20, 6)->nullable();
                $table->decimal('tokens_paid', 20, 4)->default(0);
                $table->unsignedBigInteger('promo_payment_id')->nullable();
                $table->text('criteria_snapshot')->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'tier_id']);
            });
        }

        \App\Models\FomRoyalTier::ensureTableAndData();
    }

    public function down()
    {
        Schema::dropIfExists('fom_royal_awards');
        Schema::dropIfExists('fom_royal_tiers');
    }
}
