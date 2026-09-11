<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ChartAccount;
use App\Models\TokenSaving;
use App\Rules\ValidTransactionPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokenSavingController extends Controller
{
    /**
     * Display the Saving Token wallet page.
     *
     * Shows:
     *  - Current SAVING_TOKEN balance
     *  - Available Token balance (to deposit from)
     *  - Active savings deposits (with 6-mo countdown)
     *  - Matured savings (ready to withdraw)
     *  - Loan eligibility notice (shown once any saving has matured).
     */
    public function index()
    {
        $user = Auth::user();

        $availableToken = (float) $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');
        $savingToken    = (float) $user->ChartAccount()->where('acc_type', 'SAVING_TOKEN')->sum('amount');

        $activeSavings = TokenSaving::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderBy('start_date', 'desc')
            ->get()
            ->each(function ($s) {
                // Auto-flag as matured if date passed but not yet moved.
                if ($s->isMatured() && $s->status !== 'matured') {
                    $s->status = 'matured';
                    $s->save();
                }
            });

        $maturedSavings = TokenSaving::where('user_id', $user->id)
            ->whereIn('status', ['matured', 'active'])
            ->get()
            ->filter(fn($s) => $s->isMatured())
            ->values();

        $withdrawnSavings = TokenSaving::where('user_id', $user->id)
            ->where('status', 'withdrawn')
            ->orderBy('withdrawn_date', 'desc')
            ->limit(10)
            ->get();

        $totalSaving         = $savingToken;
        $totalMatured        = (float) $maturedSavings->sum('amount') - (float) $maturedSavings->sum('withdrawn_amount');
        $loanEligible        = $totalMatured > 0;
        $loanEligibleDisplay = $loanEligible;

        return view('user.token-saving', compact(
            'availableToken', 'savingToken', 'activeSavings', 'maturedSavings',
            'withdrawnSavings', 'totalSaving', 'totalMatured', 'loanEligible'
        ));
    }

    /**
     * Move tokens from AVAILABLE_TOKEN into SAVING_TOKEN (6-month lock).
     */
    public function deposit(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'amount'               => 'required|numeric|min:0.01',
            'transaction_password' => ['required', new ValidTransactionPassword(Auth::user())],
        ], [
            'amount.min' => 'Please enter an amount greater than 0 to save.',
        ]);

        $amount = round((float) $request->amount, 4);

        try {
            DB::transaction(function () use ($user, $amount) {
                $avail = (float) ChartAccount::where('user_id', $user->id)
                    ->where('acc_type', 'AVAILABLE_TOKEN')
                    ->lockForUpdate()
                    ->sum('amount');

                if ($amount > $avail + 0.0001) {
                    throw new \RuntimeException('Insufficient Available Token balance. You have ' . number_format($avail, 2) . ' tokens available.');
                }

                // Debit AVAILABLE_TOKEN, credit SAVING_TOKEN.
                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                    ['amount'  => round(max(0, $avail - $amount), 4)]
                );
                $existingSaving = (float) ChartAccount::where('user_id', $user->id)
                    ->where('acc_type', 'SAVING_TOKEN')
                    ->sum('amount');
                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'SAVING_TOKEN'],
                    ['amount'  => round($existingSaving + $amount, 4)]
                );

                $start  = Carbon::now()->startOfDay();
                $mature = (clone $start)->addMonthsNoOverflow(TokenSaving::LOCK_MONTHS);

                TokenSaving::create([
                    'user_id'      => $user->id,
                    'amount'       => $amount,
                    'start_date'   => $start->toDateString(),
                    'mature_date'  => $mature->toDateString(),
                    'status'       => 'active',
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', number_format($amount, 2) . ' tokens moved to Saving Token successfully. They will mature (become withdrawable & loan-eligible) in 6 months.');
    }

    /**
     * Move matured tokens back from SAVING_TOKEN to AVAILABLE_TOKEN.
     */
    public function withdraw(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'saving_id'            => 'required|integer|exists:token_savings,id',
            'amount'               => 'nullable|numeric|min:0.01',
            'transaction_password' => ['required', new ValidTransactionPassword(Auth::user())],
        ]);

        $saving = TokenSaving::where('id', $request->saving_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$saving->isMatured()) {
            return back()->with('error', 'This saving has not matured yet. Tokens are locked for 6 months.');
        }

        $remaining = round((float) $saving->amount - (float) $saving->withdrawn_amount, 4);
        $amount    = $request->filled('amount') ? round((float) $request->amount, 4) : $remaining;
        if ($amount <= 0 || $amount > $remaining + 0.0001) {
            return back()->with('error', 'Invalid withdrawal amount for this saving.');
        }

        try {
            DB::transaction(function () use ($user, $saving, $amount, $remaining) {
                $savingBal = (float) ChartAccount::where('user_id', $user->id)
                    ->where('acc_type', 'SAVING_TOKEN')
                    ->lockForUpdate()
                    ->sum('amount');

                if ($amount > $savingBal + 0.0001) {
                    throw new \RuntimeException('Saving Token balance mismatch. Please refresh and try again.');
                }

                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'SAVING_TOKEN'],
                    ['amount'  => round(max(0, $savingBal - $amount), 4)]
                );
                $availBal = (float) ChartAccount::where('user_id', $user->id)
                    ->where('acc_type', 'AVAILABLE_TOKEN')
                    ->sum('amount');
                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                    ['amount'  => round($availBal + $amount, 4)]
                );

                $newWithdrawn = round((float) $saving->withdrawn_amount + $amount, 4);
                $saving->withdrawn_amount = $newWithdrawn;
                $saving->withdrawn_date   = Carbon::now()->toDateString();
                if ($newWithdrawn + 0.0001 >= (float) $saving->amount) {
                    $saving->status = 'withdrawn';
                } else {
                    $saving->status = 'matured'; // partially withdrawn stays matured
                }
                $saving->save();
            });
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', number_format($amount, 2) . ' tokens moved from Saving Token back to Available Token.');
    }
}
