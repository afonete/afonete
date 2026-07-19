<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialFieldsToTeamLeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('team_leaders')) {
            Schema::table('team_leaders', function (Blueprint $table) {
                if (!Schema::hasColumn('team_leaders', 'whatsapp')) {
                    $table->string('whatsapp')->nullable()->after('status')
                          ->comment('WhatsApp link or number to complete team leader application');
                }
                if (!Schema::hasColumn('team_leaders', 'instagram')) {
                    $table->string('instagram')->nullable()->after('whatsapp')
                          ->comment('Instagram link/profile to complete team leader application');
                }
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
        if (Schema::hasTable('team_leaders')) {
            Schema::table('team_leaders', function (Blueprint $table) {
                if (Schema::hasColumn('team_leaders', 'whatsapp')) {
                    $table->dropColumn('whatsapp');
                }
                if (Schema::hasColumn('team_leaders', 'instagram')) {
                    $table->dropColumn('instagram');
                }
            });
        }
    }
}
