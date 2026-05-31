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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('user')->nullable();
    
            $table->string('activation')->unique()->nullable();
            $table->string('gender')->default('custom')->null();
            $table->string('country')->default('Rwanda');
            $table->string('referee_id')->default('System')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('contract')->default('not');

            $table->string('has_paid_package')->default('no')->comment('do user has paid for any package');
            $table->string('has_free_package')->default('no')->comment('do user allowed a free package');
            $table->string('utype')->default('USR')->comment('ADM for admin and USR for user or customer');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};