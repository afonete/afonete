<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to repair referral links, team side placements, and retroactive referral bonuses.
     */
    public function up(): void
    {
        if (Schema::hasTable('teams') && Schema::hasTable('users')) {
            // 1. Repair users where Teams record exists (pointing to user_id) but users.referee_id = 0
            $teams = DB::table('teams')->get();
            foreach ($teams as $t) {
                if ($t->user_id && $t->team_user_id) {
                    DB::table('users')
                        ->where('id', $t->team_user_id)
                        ->where(function ($q) {
                            $q->where('referee_id', 0)->orWhereNull('referee_id');
                        })
                        ->update(['referee_id' => $t->user_id]);
                }
            }

            // 2. Repair users where referee_id > 0 but NO Teams record exists
            $referredUsers = DB::table('users')->where('referee_id', '>', 0)->get();
            foreach ($referredUsers as $u) {
                $hasTeam = DB::table('teams')->where('team_user_id', $u->id)->exists();
                if (!$hasTeam) {
                    $leftCount = DB::table('teams')->where('user_id', $u->referee_id)->where('side', 'LEFT')->count();
                    $rightCount = DB::table('teams')->where('user_id', $u->referee_id)->where('side', 'RIGHT')->count();
                    $side = ($leftCount <= $rightCount) ? 'LEFT' : 'RIGHT';

                    DB::table('teams')->insert([
                        'user_id'      => $u->referee_id,
                        'team_user_id' => $u->id,
                        'side'         => $side,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }

            // 3. Retroactively credit missing referral bonuses for all past confirmed payments
            if (Schema::hasTable('payments') && Schema::hasTable('referral_bonuses')) {
                $payments = \App\Models\Payment::where('status', 1)->get();
                foreach ($payments as $p) {
                    \App\Services\ReferralService::creditForPayment($p);
                }
            }
        }
    }

    public function down(): void
    {
        // No reversal needed for self-repair
    }
};
