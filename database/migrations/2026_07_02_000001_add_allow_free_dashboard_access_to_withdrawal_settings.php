<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowFreeDashboardAccessToWithdrawalSettings extends Migration
{
    public function up()
    {
        Schema::table('withdrawal_settings', function (Blueprint $table) {
            $table->boolean('allow_free_dashboard_access')->default(false)
                  ->comment('When true, users automatically get free standard account access to dashboard after signup');
        });
    }

    public function down()
    {
        Schema::table('withdrawal_settings', function (Blueprint $table) {
            $table->dropColumn('allow_free_dashboard_access');
        });
    }
}
