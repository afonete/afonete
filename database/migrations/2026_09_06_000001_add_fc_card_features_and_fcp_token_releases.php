<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adds display metadata columns to fcspackages so admins can configure
     * the feature bullets shown on each FC VIP card, and adds the
     * `fcp_token_releases` table for the 12-month monthly locked-token
     * release schedule that FC VIP purchasers receive.
     */
    public function up(): void
    {
        if (Schema::hasTable('fcspackages')) {
            $cols = [
                'loan_min'        => 'Loan minimum (USD)',
                'loan_max'        => 'Loan maximum (USD)',
                'ads_credits'     => 'Number of ad credits granted',
                'free_shop_room'  => 'Whether free shop room online is included (0/1)',
            ];
            Schema::table('fcspackages', function (Blueprint $table) {
                if (!Schema::hasColumn('fcspackages', 'loan_min')) {
                    $table->unsignedDecimal('loan_min', 12, 2)->nullable()->after('default_token');
                }
                if (!Schema::hasColumn('fcspackages', 'loan_max')) {
                    $table->unsignedDecimal('loan_max', 12, 2)->nullable()->after('loan_min');
                }
                if (!Schema::hasColumn('fcspackages', 'ads_credits')) {
                    $table->unsignedBigInteger('ads_credits')->nullable()->after('loan_max');
                }
                if (!Schema::hasColumn('fcspackages', 'free_shop_room')) {
                    $table->boolean('free_shop_room')->default(true)->after('ads_credits');
                }
            });
        }

        if (!Schema::hasTable('fcp_token_releases')) {
            Schema::create('fcp_token_releases', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('payment_id')->nullable()->index();
                $table->unsignedBigInteger('package_id')->nullable()->index();
                $table->unsignedDecimal('total_tokens', 18, 4)->default(0);
                $table->unsignedDecimal('monthly_amount', 18, 4)->default(0);
                $table->unsignedInteger('total_months')->default(12);
                $table->unsignedInteger('months_released')->default(0);
                $table->unsignedDecimal('released_tokens', 18, 4)->default(0);
                $table->date('start_date')->nullable();
                $table->date('next_release_date')->nullable();
                $table->date('end_date')->nullable();
                $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
                $table->timestamps();

                $table->index(['user_id', 'status', 'next_release_date'], 'fcp_rel_user_status_next_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fcp_token_releases');
        if (Schema::hasTable('fcspackages')) {
            Schema::table('fcspackages', function (Blueprint $table) {
                foreach (['loan_min','loan_max','ads_credits','free_shop_room'] as $c) {
                    if (Schema::hasColumn('fcspackages', $c)) {
                        $table->dropColumn($c);
                    }
                }
            });
        }
    }
};
