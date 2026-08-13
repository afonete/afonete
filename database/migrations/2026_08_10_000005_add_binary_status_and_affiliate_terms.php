<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBinaryStatusAndAffiliateTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'binary_status')) {
                    $table->string('binary_status')->default('inactive');
                }
                if (!Schema::hasColumn('users', 'affiliate_terms_accepted_at')) {
                    $table->timestamp('affiliate_terms_accepted_at')->nullable();
                }
            });
        }

        if (!Schema::hasTable('affiliate_terms')) {
            Schema::create('affiliate_terms', function (Blueprint $table) {
                $table->id();
                $table->longText('terms_content')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('users', 'binary_status')) $cols[] = 'binary_status';
                if (Schema::hasColumn('users', 'affiliate_terms_accepted_at')) $cols[] = 'affiliate_terms_accepted_at';
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }

        Schema::dropIfExists('affiliate_terms');
    }
}
