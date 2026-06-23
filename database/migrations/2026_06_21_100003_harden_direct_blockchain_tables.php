<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HardenDirectBlockchainTables extends Migration
{
    public function up()
    {
        if (Schema::hasTable('deposits')) {
            Schema::table('deposits', function (Blueprint $table) {
                if (!Schema::hasColumn('deposits', 'deposit_address')) {
                    $table->string('deposit_address', 100)->nullable()->after('user_wallet_address');
                }
                if (!Schema::hasColumn('deposits', 'confirmations')) {
                    $table->unsignedInteger('confirmations')->default(0)->after('blockchain_tx_hash');
                }
                if (!Schema::hasColumn('deposits', 'detected_at')) {
                    $table->timestamp('detected_at')->nullable()->after('confirmations');
                }
                if (!Schema::hasColumn('deposits', 'credited_at')) {
                    $table->timestamp('credited_at')->nullable()->after('detected_at');
                }
            });

            $this->safeIndex('deposits', 'deposits_blockchain_hash_unique', 'CREATE UNIQUE INDEX deposits_blockchain_hash_unique ON deposits (blockchain_tx_hash)');
            $this->safeIndex('deposits', 'deposits_deposit_address_status_idx', 'CREATE INDEX deposits_deposit_address_status_idx ON deposits (deposit_address, status)');
        }

        if (Schema::hasTable('withdrawals')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                if (!Schema::hasColumn('withdrawals', 'blockchain_tx_hash')) {
                    $table->string('blockchain_tx_hash')->nullable()->after('txn_hash');
                }
                if (!Schema::hasColumn('withdrawals', 'approval_required')) {
                    $table->boolean('approval_required')->default(true)->after('status');
                }
                if (!Schema::hasColumn('withdrawals', 'risk_score')) {
                    $table->unsignedInteger('risk_score')->default(0)->after('approval_required');
                }
                if (!Schema::hasColumn('withdrawals', 'risk_flags')) {
                    $table->json('risk_flags')->nullable()->after('risk_score');
                }
                if (!Schema::hasColumn('withdrawals', 'idempotency_key')) {
                    $table->string('idempotency_key', 100)->nullable()->after('transaction_no');
                }
                if (!Schema::hasColumn('withdrawals', 'attempts')) {
                    $table->unsignedInteger('attempts')->default(0)->after('risk_flags');
                }
                if (!Schema::hasColumn('withdrawals', 'locked_at')) {
                    $table->timestamp('locked_at')->nullable()->after('attempts');
                }
                if (!Schema::hasColumn('withdrawals', 'last_attempt_at')) {
                    $table->timestamp('last_attempt_at')->nullable()->after('locked_at');
                }
                if (!Schema::hasColumn('withdrawals', 'failure_reason')) {
                    $table->text('failure_reason')->nullable()->after('last_attempt_at');
                }
                if (!Schema::hasColumn('withdrawals', 'signer_request_id')) {
                    $table->string('signer_request_id', 100)->nullable()->after('failure_reason');
                }
                if (!Schema::hasColumn('withdrawals', 'signer_response')) {
                    $table->json('signer_response')->nullable()->after('signer_request_id');
                }
            });

            $this->safeIndex('withdrawals', 'withdrawals_blockchain_hash_unique', 'CREATE UNIQUE INDEX withdrawals_blockchain_hash_unique ON withdrawals (blockchain_tx_hash)');
            $this->safeIndex('withdrawals', 'withdrawals_idempotency_unique', 'CREATE UNIQUE INDEX withdrawals_idempotency_unique ON withdrawals (idempotency_key)');
            $this->safeIndex('withdrawals', 'withdrawals_status_method_network_idx', 'CREATE INDEX withdrawals_status_method_network_idx ON withdrawals (status, method, network)');
        }

        if (Schema::hasTable('withdrawal_settings')) {
            Schema::table('withdrawal_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('withdrawal_settings', 'auto_withdrawals_enabled')) {
                    $table->boolean('auto_withdrawals_enabled')->default(false)->after('require_admin_approval');
                }
                if (!Schema::hasColumn('withdrawal_settings', 'admin_approval_threshold')) {
                    $table->decimal('admin_approval_threshold', 12, 2)->default(100)->after('auto_withdrawals_enabled');
                }
                if (!Schema::hasColumn('withdrawal_settings', 'manual_review_risk_score')) {
                    $table->unsignedInteger('manual_review_risk_score')->default(50)->after('admin_approval_threshold');
                }
                if (!Schema::hasColumn('withdrawal_settings', 'max_auto_withdrawal')) {
                    $table->decimal('max_auto_withdrawal', 12, 2)->default(100)->after('manual_review_risk_score');
                }
                if (!Schema::hasColumn('withdrawal_settings', 'hot_wallet_max_balance')) {
                    $table->decimal('hot_wallet_max_balance', 18, 6)->nullable()->after('max_auto_withdrawal');
                }
                if (!Schema::hasColumn('withdrawal_settings', 'hot_wallet_reserve_balance')) {
                    $table->decimal('hot_wallet_reserve_balance', 18, 6)->default(100)->after('hot_wallet_max_balance');
                }
                if (!Schema::hasColumn('withdrawal_settings', 'cold_wallet_address')) {
                    $table->string('cold_wallet_address', 100)->nullable()->after('hot_wallet_reserve_balance');
                }
            });
        }
    }

    public function down()
    {
        // Keep columns on rollback to avoid accidental loss of audit/security data.
    }

    private function safeIndex(string $table, string $name, string $sql): void
    {
        try {
            DB::statement($sql);
        } catch (\Throwable $e) {
            // Index probably already exists or the DB driver does not support the syntax.
        }
    }
}
