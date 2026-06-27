<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionPasswordToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'transaction_password')) {
                $table->string('transaction_password')->nullable()->after('password');
            }
            if (! Schema::hasColumn('users', 'transaction_password_set_at')) {
                $table->timestamp('transaction_password_set_at')->nullable()->after('transaction_password');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'transaction_password_set_at')) {
                $table->dropColumn('transaction_password_set_at');
            }
            if (Schema::hasColumn('users', 'transaction_password')) {
                $table->dropColumn('transaction_password');
            }
        });
    }
}
