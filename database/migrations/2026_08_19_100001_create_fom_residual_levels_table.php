<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFomResidualLevelsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('fom_residual_levels')) {
            Schema::create('fom_residual_levels', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('level')->unique();
                $table->unsignedInteger('required_members');
                $table->unsignedInteger('per_parent')->default(2);
                $table->decimal('income_percent', 8, 3);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fom_residual_approvals')) {
            Schema::create('fom_residual_approvals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedInteger('level');
                $table->string('status', 20)->default('pending');
                $table->text('members_snapshot')->nullable();
                $table->timestamp('detected_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'level']);
            });
        }

        \App\Models\FomResidualLevel::ensureTableAndData();
    }

    public function down()
    {
        Schema::dropIfExists('fom_residual_approvals');
        Schema::dropIfExists('fom_residual_levels');
    }
}
