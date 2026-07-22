<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('super_leader_credits')) {
            return;
        }

        Schema::table('super_leader_credits', function (Blueprint $table) {
            if (!Schema::hasColumn('super_leader_credits', 'turnover_milestones_reached')) {
                $table->integer('turnover_milestones_reached')
                    ->default(0)
                    ->after('auto_withdrawal_percent')
                    ->comment('How many turnover % milestones have been triggered');
            }

            if (!Schema::hasColumn('super_leader_credits', 'last_turnover')) {
                $table->decimal('last_turnover', 12, 2)
                    ->default(0)
                    ->after('turnover_milestones_reached')
                    ->comment('Last recorded referral turnover');
            }

            if (!Schema::hasColumn('super_leader_credits', 'pending_cashout')) {
                $table->decimal('pending_cashout', 12, 2)
                    ->default(0)
                    ->after('last_turnover')
                    ->comment('Amount released, waiting 5-min timer before cashout');
            }

            if (!Schema::hasColumn('super_leader_credits', 'pending_cashout_at')) {
                $table->timestamp('pending_cashout_at')
                    ->nullable()
                    ->after('pending_cashout')
                    ->comment('When the 5-min cashout timer started');
            }

            if (!Schema::hasColumn('super_leader_credits', 'auto_withdrawal_processed')) {
                $table->boolean('auto_withdrawal_processed')
                    ->default(false)
                    ->after('pending_cashout_at')
                    ->comment('Whether the 1-hr auto withdrawal was done');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('super_leader_credits')) {
            return;
        }

        Schema::table('super_leader_credits', function (Blueprint $table) {
            $columns = [
                'turnover_milestones_reached',
                'last_turnover',
                'pending_cashout',
                'pending_cashout_at',
                'auto_withdrawal_processed',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('super_leader_credits', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};