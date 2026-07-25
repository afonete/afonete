<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('team_leader_token_releases')) {
            Schema::create('team_leader_token_releases', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('team_leader_id')->nullable()->index();
                $table->unsignedBigInteger('activation_id')->nullable()->index();
                $table->decimal('total_locked_tokens', 15, 2)->default(0);
                $table->decimal('released_tokens', 15, 2)->default(0);
                $table->decimal('remaining_locked_tokens', 15, 2)->default(0);
                $table->string('status')->default('pending')->comment('pending, approved, rejected');
                $table->integer('duration_days')->default(60);
                $table->timestamp('activated_at')->nullable();
                $table->timestamp('eligible_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_leader_token_releases');
    }
};
