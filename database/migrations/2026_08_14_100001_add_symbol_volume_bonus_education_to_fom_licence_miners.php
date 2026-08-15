<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * FOM Licence Miner package configuration additions:
 *
 *  - token_symbol      : per-package token symbol (nullable → falls back to
 *                        the global TokenSetting symbol when empty).
 *  - volume_bonus      : now a SEPARATE field from volume_point. Legacy rows
 *                        stored a "bonus" in volume_point when > 10; those
 *                        values are migrated into volume_bonus.
 *  - education_access  : "Access to Education Courses" benefit line shown on
 *                        every FOM package (admin-editable text).
 */
class AddSymbolVolumeBonusEducationToFomLicenceMiners extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('fom_licence_miners')) {
            return; // runtime ensureTableAndData() creates the full schema
        }

        if (!Schema::hasColumn('fom_licence_miners', 'volume_bonus')) {
            Schema::table('fom_licence_miners', function (Blueprint $table) {
                $table->decimal('volume_bonus', 20, 0)->default(0)->after('volume_point');
            });

            // Legacy convention: volume_point > 10 actually held a Volume Bonus.
            DB::table('fom_licence_miners')
                ->where('volume_point', '>', 10)
                ->update([
                    'volume_bonus' => DB::raw('volume_point'),
                    'volume_point' => 0,
                ]);
        }

        if (!Schema::hasColumn('fom_licence_miners', 'token_symbol')) {
            Schema::table('fom_licence_miners', function (Blueprint $table) {
                $table->string('token_symbol', 50)->nullable()->after('tokens');
            });
        }

        if (!Schema::hasColumn('fom_licence_miners', 'education_access')) {
            Schema::table('fom_licence_miners', function (Blueprint $table) {
                $table->string('education_access')->nullable()->default('Access to Education Courses')->after('volume_bonus');
            });

            DB::table('fom_licence_miners')->update([
                'education_access' => 'Access to Education Courses',
            ]);
        }
    }

    public function down()
    {
        if (!Schema::hasTable('fom_licence_miners')) {
            return;
        }

        Schema::table('fom_licence_miners', function (Blueprint $table) {
            if (Schema::hasColumn('fom_licence_miners', 'volume_bonus')) {
                $table->dropColumn('volume_bonus');
            }
            if (Schema::hasColumn('fom_licence_miners', 'token_symbol')) {
                $table->dropColumn('token_symbol');
            }
            if (Schema::hasColumn('fom_licence_miners', 'education_access')) {
                $table->dropColumn('education_access');
            }
        });
    }
}
