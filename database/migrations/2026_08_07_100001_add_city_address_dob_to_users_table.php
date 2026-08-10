<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityAddressDobToUsersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'city')) {
                    $table->string('city', 100)->nullable()->after('country');
                }
                if (!Schema::hasColumn('users', 'address')) {
                    $table->text('address')->nullable()->after('city');
                }
                if (!Schema::hasColumn('users', 'dob')) {
                    $table->date('dob')->nullable()->after('address');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'city')) $table->dropColumn('city');
                if (Schema::hasColumn('users', 'address')) $table->dropColumn('address');
                if (Schema::hasColumn('users', 'dob')) $table->dropColumn('dob');
            });
        }
    }
}
