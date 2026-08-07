<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycVerificationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('kyc_verifications')) {
            Schema::create('kyc_verifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();

                $table->integer('overall_percentage')->default(0)->comment('25%, 75%, 100%');
                $table->string('status')->default('unsubmitted')->comment('unsubmitted | pending | partially_approved | approved | rejected');

                // Level 1: Basic Verification (25%) - Phone Number
                $table->string('level_1_status')->default('unsubmitted');
                $table->string('phone_number')->nullable();
                $table->timestamp('level_1_submitted_at')->nullable();
                $table->timestamp('level_1_approved_at')->nullable();
                $table->text('level_1_admin_notes')->nullable();

                // Level 2: Identity Verification (75%) - Government ID, Selfie, DOB
                $table->string('level_2_status')->default('unsubmitted');
                $table->string('id_type')->nullable();
                $table->string('id_front_path')->nullable();
                $table->string('id_back_path')->nullable();
                $table->string('selfie_path')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->timestamp('level_2_submitted_at')->nullable();
                $table->timestamp('level_2_approved_at')->nullable();
                $table->text('level_2_admin_notes')->nullable();

                // Level 3: Address Verification (100%) - Utility Bill, Bank Statement, Gov Proof
                $table->string('level_3_status')->default('unsubmitted');
                $table->string('address_doc_type')->nullable();
                $table->string('address_doc_path')->nullable();
                $table->text('full_address')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->nullable();
                $table->timestamp('level_3_submitted_at')->nullable();
                $table->timestamp('level_3_approved_at')->nullable();
                $table->text('level_3_admin_notes')->nullable();

                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Add non_kyc_fee_amount column to withdrawal_settings
        if (Schema::hasTable('withdrawal_settings') && !Schema::hasColumn('withdrawal_settings', 'non_kyc_fee_amount')) {
            Schema::table('withdrawal_settings', function (Blueprint $table) {
                $table->decimal('non_kyc_fee_amount', 12, 2)->default(10.00)->after('withdrawal_fee_percent')
                      ->comment('Fee amount required or deducted if user has not completed KYC verification');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('kyc_verifications');
    }
}
