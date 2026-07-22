<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('team_leader_events')) {
            Schema::table('team_leader_events', function (Blueprint $table) {
                $cols = [
                    'event_image_1'  => ['type' => 'string', 'nullable' => true, 'after' => 'proof_files'],
                    'event_image_2'  => ['type' => 'string', 'nullable' => true, 'after' => 'event_image_1'],
                    'event_done_on'  => ['type' => 'date',   'nullable' => true, 'after' => 'event_image_2'],
                    'hotel_location' => ['type' => 'string', 'nullable' => true, 'after' => 'event_done_on'],
                    'zoom_link'      => ['type' => 'string', 'nullable' => true, 'after' => 'hotel_location'],
                    'country'        => ['type' => 'string', 'nullable' => true, 'after' => 'zoom_link'],
                    'place'          => ['type' => 'string', 'nullable' => true, 'after' => 'country'],
                    'location'       => ['type' => 'string', 'nullable' => true, 'after' => 'place'],
                    'event_date'     => ['type' => 'date',   'nullable' => true, 'after' => 'location'],
                    'event_type'     => ['type' => 'string', 'nullable' => true, 'default' => 'plan', 'after' => 'event_date'],
                ];
                foreach ($cols as $col => $def) {
                    if (!Schema::hasColumn('team_leader_events', $col)) {
                        if ($def['type'] === 'string') {
                            $table->string($col)->nullable()->after($def['after'] ?? null);
                        } elseif ($def['type'] === 'date') {
                            $table->date($col)->nullable()->after($def['after'] ?? null);
                        }
                    }
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('team_leader_events')) {
            Schema::table('team_leader_events', function (Blueprint $table) {
                $cols = ['event_image_1','event_image_2','event_done_on','hotel_location','zoom_link','country','place','location','event_date','event_type'];
                foreach ($cols as $c) {
                    if (Schema::hasColumn('team_leader_events', $c)) $table->dropColumn($c);
                }
            });
        }
    }
};
