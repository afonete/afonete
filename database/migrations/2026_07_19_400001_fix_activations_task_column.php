<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixActivationsTaskColumn extends Migration
{
    public function up()
    {
        if (Schema::hasTable('activations') && Schema::hasColumn('activations', 'task')) {
            DB::statement('ALTER TABLE `activations` MODIFY `task` TEXT NULL');
        }
    }

    public function down()
    {
        if (Schema::hasTable('activations') && Schema::hasColumn('activations', 'task')) {
            DB::statement('ALTER TABLE `activations` MODIFY `task` VARCHAR(255) NULL');
        }
    }
}
