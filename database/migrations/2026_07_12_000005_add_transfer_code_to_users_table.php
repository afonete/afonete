<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransferCodeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'transfer_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('transfer_code', 10)->nullable()->after('email_verification_pin')
                      ->comment('7-digit transfer code number generated upon registration');
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
        if (Schema::hasColumn('users', 'transfer_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('transfer_code');
            });
        }
    }
}
