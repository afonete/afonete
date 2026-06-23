<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockchainAuditLogsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('blockchain_audit_logs')) {
            return;
        }

        Schema::create('blockchain_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event', 100);
            $table->string('level', 20)->default('info');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->string('tx_hash')->nullable();
            $table->string('address', 100)->nullable();
            $table->decimal('amount', 20, 6)->nullable();
            $table->string('currency', 20)->nullable();
            $table->string('network', 30)->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->string('request_id', 100)->nullable();
            $table->text('message')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();

            $table->index(['event', 'created_at'], 'bal_event_created_idx');
            $table->index(['user_id', 'created_at'], 'bal_user_created_idx');
            $table->index(['auditable_type', 'auditable_id'], 'bal_auditable_idx');
            $table->index('tx_hash');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blockchain_audit_logs');
    }
}
