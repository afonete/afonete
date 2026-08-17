<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FomRedeemOption;
use App\Models\FomRedeemLog;
use App\Models\ChartAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * /user/rewards: redeem TOTAL VOLUME POINT for USDT.
 * Points deducted from the VOLUME_POINT wallet, USDT credited to CASHOUT
 * immediately — atomic, row-locked, race-safe.
 */
class FomRedeemController extends Controller
{
    public function index()
    {
        FomRedeemOption::ensureTableAndData();

        $user = Auth::user();

        $volumePoints = (float) ChartAccount::where('user_id', $user->id)
            ->where('acc_type', 'VOLUME_POINT')->sum('amount');

        $options = FomRedeemOption::where('is_active', true)
            ->orderBy('sort_order')->orderBy('points_required')
            ->get();

        $myRedemptions = FomRedeemLog::where('user_id', $user->id)
            ->orderByDesc('redeemed_at')
            ->limit(10)
            ->get();

        return view('user.rewards', compact('volumePoints', 'options', 'myRedemptions'));
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'option_id' => 'required|integer',
        ]);

        FomRedeemOption::ensureTableAndData();

        $user = Auth::user();

        // Only active options are redeemable (direct POSTs can't use disabled ones)
        $option = FomRedeemOption::where('id', $request->option_id)
            ->where('is_active', true)
            ->first();
        if (!$option) {
            return back()->with('error', 'This redemption option is not available.');
        }

        try {
            DB::transaction(function () use ($user, $option) {
                // Strict, row-locked deduction: throws on insufficient points,
                // rolling the whole redemption back (race-safe double-spend guard).
                ChartAccount::debitLocked(
                    $user->id,
                    'VOLUME_POINT',
                    (float) $option->points_required,
                    true,
                    "Redeem {$option->points_required} points → {$option->usdt_amount} USDT"
                );

                // USDT → CASHOUT immediately
                ChartAccount::creditLocked(
                    $user->id,
                    'CASHOUT',
                    (float) $option->usdt_amount,
                    "Volume point redemption ({$option->points_required} pts)"
                );

                FomRedeemLog::create([
                    'user_id'       => $user->id,
                    'option_id'     => $option->id,
                    'points_spent'  => $option->points_required,
                    'usdt_received' => $option->usdt_amount,
                    'redeemed_at'   => Carbon::now(),
                ]);

                try {
                    \App\Models\Transaction::create([
                        'user_id'             => $user->id,
                        'transaction_no'      => method_exists(\App\Models\Transaction::class, 'generateTransactionNo')
                            ? \App\Models\Transaction::generateTransactionNo()
                            : 'FOM-RDM-' . time() . '-' . rand(100, 999),
                        'transaction_type'    => 'FOM_VOLUME_POINT_REDEEM',
                        'transaction_details' => json_encode([
                            'points_spent'  => (float) $option->points_required,
                            'usdt_received' => (float) $option->usdt_amount,
                            'to'            => 'CASHOUT',
                            'date'          => Carbon::now()->toDateTimeString(),
                        ]),
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('Redeem transaction log failed: ' . $e->getMessage());
                }
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Insufficient Volume Points: you need ' . number_format((float) $option->points_required) . ' points for this reward.');
        } catch (\Throwable $e) {
            Log::error('Volume point redemption failed: ' . $e->getMessage());
            return back()->with('error', 'Redemption failed and no points were deducted. Please try again.');
        }

        return back()->with('success', number_format((float) $option->points_required) . ' Volume Points redeemed — ' . number_format((float) $option->usdt_amount, 2) . ' USDT credited to your Cashout balance!');
    }
}
