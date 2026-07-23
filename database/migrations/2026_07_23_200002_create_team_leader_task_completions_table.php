<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Persists the tick state of each ASSIGNED TASK for a team leader.
     *
     * Tasks are stored as a single newline-separated TEXT field on the
     * activations table, so we identify each task by a normalized hash of
     * its text (sha1 of the lowercased, trimmed line). This keeps the tick
     * stable even if the task list is reordered, and survives browser/localStorage
     * resets so admin Performance Monitoring sees the real state.
     */
    public function up()
    {
        if (!Schema::hasTable('team_leader_task_completions')) {
            Schema::create('team_leader_task_completions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('task_hash', 40)->comment('sha1 of normalized task text');
                $table->text('task_text')->nullable();
                $table->boolean('is_completed')->default(false);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                // One completion record per user per task.
                $table->unique(['user_id', 'task_hash'], 'uniq_user_task_hash');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('team_leader_task_completions');
    }
};
