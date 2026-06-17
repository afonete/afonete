<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Rank criteria (admin-editable). Seeded with the 5 ranks from the spec.
 *
 * Levels:
 *   1. Associate             ($1,000 reward)
 *   2. Director              ($10,000 reward)
 *   3. Regional Supervisor   ($25,000 reward)
 *   4. Regional Vice President ($1,000,000 reward)
 *   5. Associate Manager     (20% weekly of qualifying downline bonuses)
 */
class CreateRankSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('rank_settings', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->comment('associate, director, regional_supervisor, regional_vice_president, associate_manager');
            $table->string('name');
            $table->unsignedTinyInteger('level')->comment('1..5');
            $table->unsignedInteger('order')->default(0);

            // ── Criteria ────────────────────────────────────────────────
            $table->unsignedInteger('min_active_direct_referrals')->default(0);
            $table->unsignedInteger('min_associates_from_direct')->default(0)
                  ->comment('For Director / RS / RVP — how many DIRECT refs must already be Associates');
            $table->unsignedInteger('min_directors_from_direct')->default(0)
                  ->comment('For RS — how many DIRECT refs must already be Directors');
            $table->unsignedInteger('min_regional_supervisors_from_direct')->default(0)
                  ->comment('For RVP — how many DIRECT refs must already be Regional Supervisors');
            $table->decimal('min_direct_referral_investment', 14, 2)->default(0)
                  ->comment('Sum of direct refs active VENTURE package amounts (USD)');
            $table->decimal('min_total_investment', 14, 2)->default(0)
                  ->comment('Sum of direct + indirect refs active VENTURE package amounts (USD)');

            // ── Reward ─────────────────────────────────────────────────
            $table->enum('reward_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('reward_amount', 14, 2)->default(0)
                  ->comment('Fixed USD reward (Associate/Director/RS/RVP)');
            $table->decimal('reward_percentage', 5, 2)->default(0)
                  ->comment('For Associate Manager: weekly % of qualifying direct-refs referral bonuses');
            $table->unsignedInteger('am_min_active_direct_investment_users')->default(10)
                  ->comment('For AM: number of active direct refs whose investment ≥ am_per_user_min_investment');
            $table->decimal('am_per_user_min_investment', 14, 2)->default(500000)
                  ->comment('For AM: minimum per-direct-ref investment (default $500,000)');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Seed the 5 ranks from the spec ─────────────────────────────
        DB::table('rank_settings')->insert([
            [
                'slug'           => 'associate',
                'name'           => 'Associate',
                'level'          => 1,
                'order'          => 1,
                'min_active_direct_referrals' => 5,
                'min_associates_from_direct'  => 0,
                'min_directors_from_direct'   => 0,
                'min_regional_supervisors_from_direct' => 0,
                'min_direct_referral_investment' => 10000,
                'min_total_investment'         => 100000,
                'reward_type'      => 'fixed',
                'reward_amount'    => 1000,
                'reward_percentage'=> 0,
                'am_min_active_direct_investment_users' => 0,
                'am_per_user_min_investment'   => 0,
                'is_active'        => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'slug'           => 'director',
                'name'           => 'Director',
                'level'          => 2,
                'order'          => 2,
                'min_active_direct_referrals' => 0,
                'min_associates_from_direct'  => 3,
                'min_directors_from_direct'   => 0,
                'min_regional_supervisors_from_direct' => 0,
                'min_direct_referral_investment' => 50000,
                'min_total_investment'         => 500000,
                'reward_type'      => 'fixed',
                'reward_amount'    => 10000,
                'reward_percentage'=> 0,
                'am_min_active_direct_investment_users' => 0,
                'am_per_user_min_investment'   => 0,
                'is_active'        => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'slug'           => 'regional_supervisor',
                'name'           => 'Regional Supervisor',
                'level'          => 3,
                'order'          => 3,
                'min_active_direct_referrals' => 0,
                'min_associates_from_direct'  => 0,
                'min_directors_from_direct'   => 2,
                'min_regional_supervisors_from_direct' => 0,
                'min_direct_referral_investment' => 150000,
                'min_total_investment'         => 2000000,
                'reward_type'      => 'fixed',
                'reward_amount'    => 25000,
                'reward_percentage'=> 0,
                'am_min_active_direct_investment_users' => 0,
                'am_per_user_min_investment'   => 0,
                'is_active'        => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'slug'           => 'regional_vice_president',
                'name'           => 'Regional Vice President',
                'level'          => 4,
                'order'          => 4,
                'min_active_direct_referrals' => 0,
                'min_associates_from_direct'  => 0,
                'min_directors_from_direct'   => 0,
                'min_regional_supervisors_from_direct' => 5,
                'min_direct_referral_investment' => 500000,
                'min_total_investment'         => 50000000,
                'reward_type'      => 'fixed',
                'reward_amount'    => 1000000,
                'reward_percentage'=> 0,
                'am_min_active_direct_investment_users' => 0,
                'am_per_user_min_investment'   => 0,
                'is_active'        => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'slug'           => 'associate_manager',
                'name'           => 'Associate Manager',
                'level'          => 5,
                'order'          => 5,
                'min_active_direct_referrals' => 10,
                'min_associates_from_direct'  => 0,
                'min_directors_from_direct'   => 0,
                'min_regional_supervisors_from_direct' => 0,
                'min_direct_referral_investment' => 0,
                'min_total_investment'         => 0,
                'reward_type'      => 'percentage',
                'reward_amount'    => 0,
                'reward_percentage'=> 20.00,
                'am_min_active_direct_investment_users' => 10,
                'am_per_user_min_investment'   => 500000,
                'is_active'        => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('rank_settings');
    }
}
