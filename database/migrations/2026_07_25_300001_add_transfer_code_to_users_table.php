<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            if (!Schema::hasColumn('users', 'transfer_code')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('transfer_code', 10)->nullable()->unique()->index()->after('user')
                          ->comment('Unique 7-digit transfer code for token transfers between users.');
                });
            }

            // Populate 7-digit transfer_code for ALL existing users where transfer_code IS NULL or empty
            $users = DB::table('users')->where(function($q) {
                $q->whereNull('transfer_code')->orWhere('transfer_code', '');
            })->get();

            $usedCodes = DB::table('users')->whereNotNull('transfer_code')->where('transfer_code', '!=', '')->pluck('transfer_code')->toArray();

            foreach ($users as $u) {
                do {
                    $code = (string) rand(1000000, 9999999);
                } while (in_array($code, $usedCodes));

                $usedCodes[] = $code;
                DB::table('users')->where('id', $u->id)->update(['transfer_code' => $code]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'transfer_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('transfer_code');
            });
        }
    }
};
