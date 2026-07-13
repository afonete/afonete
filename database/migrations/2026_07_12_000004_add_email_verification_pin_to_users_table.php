<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailVerificationPinToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'email_verification_pin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('email_verification_pin', 10)->nullable()->after('remember_token')
                      ->comment('Random PIN code sent to email for instant activation');
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
        if (Schema::hasColumn('users', 'email_verification_pin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('email_verification_pin');
            });
        }
    }
}
