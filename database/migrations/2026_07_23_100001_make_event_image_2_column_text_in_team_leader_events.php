<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeEventImage2ColumnTextInTeamLeaderEvents extends Migration
{
    public function up()
    {
        if (Schema::hasTable('team_leader_events') && Schema::hasColumn('team_leader_events', 'event_image_2')) {
            $driver = DB::getDriverName();
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `team_leader_events` MODIFY COLUMN `event_image_2` TEXT NULL");
            } elseif ($driver === 'sqlite') {
                // SQLite natively supports any size in VARCHAR/TEXT columns;
                // but we can recreate the column as TEXT if we want, or leave as-is since SQLite doesn't enforce length limits.
            } else {
                try {
                    Schema::table('team_leader_events', function (Blueprint $table) {
                        $table->text('event_image_2')->nullable()->change();
                    });
                } catch (\Throwable $e) {
                    // Ignore or log
                }
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('team_leader_events') && Schema::hasColumn('team_leader_events', 'event_image_2')) {
            $driver = DB::getDriverName();
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `team_leader_events` MODIFY COLUMN `event_image_2` VARCHAR(255) NULL");
            }
        }
    }
}
