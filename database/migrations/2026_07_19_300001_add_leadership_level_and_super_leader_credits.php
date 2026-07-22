<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadershipLevelAndSuperLeaderCredits extends Migration
{
    public function up()
    {
        // 1. Add leadership_level to team_leaders
        if (Schema::hasTable('team_leaders') && !Schema::hasColumn('team_leaders', 'leadership_level')) {
            Schema::table('team_leaders', function (Blueprint $table) {
                $table->string('leadership_level')->default('TEAM_LEADER')->after('status')
                      ->comment('TEAM_LEADER or SUPER_LEADER');
            });
        }

        // 2. Create super_leader_credits table
        if (!Schema::hasTable('super_leader_credits')) {
            Schema::create('super_leader_credits', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('team_leader_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('activation_id')->nullable();
                $table->decimal('credit_amount', 12, 2)->default(0)->comment('Total credit allocated by admin');
                $table->decimal('remaining_credit', 12, 2)->default(0)->comment('Credit still available');
                $table->decimal('cashout_amount', 12, 2)->default(0)->comment('Amount moved to cashout/withdrawal');
                $table->decimal('sales_turnover_target', 12, 2)->default(10000)->comment('Default $10,000');
                $table->decimal('turnover_target_percent', 5, 2)->default(0)->comment('e.g. 1 means 1% of turnover target');
                $table->decimal('turnover_reward_percent', 5, 2)->default(0)->comment('e.g. 0.5 means 0.5% of credit per milestone');
                $table->decimal('auto_withdrawal_percent', 5, 2)->default(0)->comment('% of credit auto-withdrawn after 1hr of approval');
                $table->string('status')->default('pending')->comment('pending = disabled, active = enabled');
                $table->timestamp('activated_at')->nullable();
                $table->timestamps();

                $table->foreign('team_leader_id')->references('id')->on('team_leaders')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('super_leader_credits');
        if (Schema::hasTable('team_leaders') && Schema::hasColumn('team_leaders', 'leadership_level')) {
            Schema::table('team_leaders', function (Blueprint $table) {
                $table->dropColumn('leadership_level');
            });
        }
    }
}
