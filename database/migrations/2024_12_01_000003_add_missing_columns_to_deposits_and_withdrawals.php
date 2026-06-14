<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToDepositsAndWithdrawals extends Migration
{
    public function up()
    {
        // ── Deposits: add columns that exist in the model but not the migration ──
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->unique();
            }
            if (!Schema::hasColumn('deposits', 'user_wallet_address')) {
                $table->string('user_wallet_address')->nullable()->comment('Wallet address user sent from');
            }
            if (!Schema::hasColumn('deposits', 'network')) {
                $table->string('network')->nullable()->comment('e.g. TRC-20, ERC-20');
            }
            if (!Schema::hasColumn('deposits', 'comment')) {
                $table->text('comment')->nullable()->comment('Admin comment on approve/reject');
            }
            if (!Schema::hasColumn('deposits', 'proof_of_payment')) {
                $table->string('proof_of_payment')->nullable()->comment('File path of uploaded proof screenshot');
            }
        });

        // ── Withdrawals: add status index if not already there ──
        // (Table was created in a prior migration; this is safe to run again)
        if (Schema::hasTable('withdrawals') && !Schema::hasColumn('withdrawals', 'admin_note')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                $table->text('admin_note')->nullable()->comment('Admin note when approving or rejecting');
            });
        }
    }

    public function down()
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_id', 'user_wallet_address', 'network',
                'comment', 'proof_of_payment',
            ]);
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn('admin_note');
        });
    }
}
