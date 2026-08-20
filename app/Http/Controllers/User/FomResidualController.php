<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FomResidualLevel;
use App\Models\Transaction;
use App\Models\User;
use App\Services\FomResidualService;
use Illuminate\Support\Facades\Auth;

class FomResidualController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        FomResidualLevel::ensureTableAndData();

        $levels = FomResidualLevel::ordered()->get();
        $hasRank = FomResidualService::hasRank($user->id);
        $matrix = FomResidualService::matrix($user->id);

        // Submit any newly-complete levels to the admin approval queue.
        FomResidualService::detectApprovalsFor($user->id);
        $approvals = \App\Models\FomResidualApproval::where('user_id', $user->id)->get()->keyBy('level');

        // Interactive Team Tree
        $teamTree = FomResidualService::teamTree($user->id);

        // Resolve member usernames per level for display
        $memberNames = [];
        foreach ($matrix['levels'] as $lvl => $info) {
            $memberNames[$lvl] = User::whereIn('id', $info['members'])
                ->get()
                ->map(fn ($u) => $u->user ?? $u->name)
                ->all();
        }

        $myPayouts = Transaction::where('user_id', $user->id)
            ->where('transaction_type', 'FOM_RESIDUAL_MATCHING')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($t) {
                $t->parsed = json_decode((string) $t->transaction_details, true) ?: [];
                return $t;
            });

        return view('user.fom-residual', [
            'levels'      => $levels,
            'hasRank'     => $hasRank,
            'matrix'      => $matrix,
            'memberNames' => $memberNames,
            'myPayouts'   => $myPayouts,
            'approvals'   => $approvals,
            'teamTree'    => $teamTree,
        ]);
    }
}
