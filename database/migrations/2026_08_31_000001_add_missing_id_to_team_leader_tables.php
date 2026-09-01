<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * §89 — root-cause fix for UrlGenerationException on /admin/team-leaders.
 *
 * The create-migration for team_leader_events / team_leader_socials is
 * guarded by `if (!Schema::hasTable(...))`. On hosts where those tables
 * were created earlier by a manual/legacy schema WITHOUT an `id` column,
 * the guarded migration skipped silently — Eloquent then returns NULL for
 * $event->id and route('admin.team-leaders.events.reject', $event->id)
 * throws "Missing required parameter ... [Missing parameter: id]".
 *
 * MySQL/MariaDB (the live host): ALTER TABLE ADD AUTO_INCREMENT PRIMARY
 * KEY backfills sequential ids automatically — existing rows become
 * actionable immediately. SQLite cannot add a PK via ALTER, so we rebuild
 * the table there (dev/test environments only).
 */
return new class extends Migration
{
    public function up()
    {
        foreach (['team_leader_events', 'team_leader_socials'] as $tbl) {
            if (!Schema::hasTable($tbl) || Schema::hasColumn($tbl, 'id')) {
                continue; // table absent or already correct — idempotent
            }

            if (DB::connection()->getDriverName() === 'sqlite') {
                $this->rebuildSqliteTableWithId($tbl);
            } else {
                // MySQL / MariaDB: single ALTER, ids backfilled automatically.
                Schema::table($tbl, function (Blueprint $table) {
                    $table->bigIncrements('id')->first();
                });
            }
        }
    }

    public function down()
    {
        // Intentionally a no-op: dropping a primary key that rows now
        // depend on would be destructive. The column is harmless to keep.
    }

    /**
     * SQLite cannot ADD a PRIMARY KEY column — rebuild the table:
     * new table with id + old columns, copy rows (ids auto-assigned),
     * drop old, rename.
     */
    private function rebuildSqliteTableWithId(string $tbl): void
    {
        $cols = array_values(array_filter(
            Schema::getColumnListing($tbl),
            fn ($c) => strtolower($c) !== 'id'
        ));
        $colList = implode(', ', array_map(fn ($c) => '"' . $c . '"', $cols));

        DB::statement("CREATE TABLE \"{$tbl}_tmp_89\" AS SELECT * FROM \"{$tbl}\" WHERE 0");
        // build the new table: id first, then the legacy columns as TEXT-affinity
        $colDefs = implode(', ', array_map(fn ($c) => '"' . $c . '"', $cols));
        DB::statement("DROP TABLE \"{$tbl}_tmp_89\"");
        DB::statement("CREATE TABLE \"{$tbl}_new_89\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT, {$colDefs})");
        DB::statement("INSERT INTO \"{$tbl}_new_89\" ({$colList}) SELECT {$colList} FROM \"{$tbl}\"");
        DB::statement("DROP TABLE \"{$tbl}\"");
        DB::statement("ALTER TABLE \"{$tbl}_new_89\" RENAME TO \"{$tbl}\"");
    }
};
