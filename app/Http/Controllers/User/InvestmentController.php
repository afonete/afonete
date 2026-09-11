<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Adventures;
use App\Models\FCpackage;
use App\Models\PackageRenewal;
use App\Models\Payment as Paymodel;
use App\Models\Transaction;
use App\Models\User;
use App\Models\DailyIncome;
use App\Services\ReferralService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * InvestmentController
 *
 * Lets the user see ALL their packages (UVP + FC), with per-investment detail
 * (locked tokens granted, renewals done, daily income, expiration, etc).
 */
class InvestmentController extends Controller
{
    /**
     * List all of the user's investments (packages they have bought).
     */
    public function index()
    {
        $user = Auth::user();

        // UVP / VENTURE packages ONLY. FC VIP packages have their own dedicated
        // "FC Packages" page under Packages → FC Packages, and are intentionally
        // excluded from this listing (FC is lifetime, never renews, uses a
        // separate 12-month token-vesting schedule — not daily ROI / renewals).
        // FOM Licence Miner packages are also excluded (managed on their own page).
        $baseQuery = Paymodel::where('user', $user->id)
            ->excludeFom()
            ->excludeFc();

        $investments = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->paginate(10);

        $totals = [
            'total_count'    => (int) (clone $baseQuery)->count(),
            'active_count'   => (int) (clone $baseQuery)
                                     ->where('is_expired', false)
                                     ->where('status', 1)
                                     ->count(),
            'total_invested' => (float) (clone $baseQuery)
                                     ->where('status', 1)
                                     ->sum('amount'),
            'active_amount'  => (float) (clone $baseQuery)
                                     ->where('is_expired', false)
                                     ->where('status', 1)
                                     ->sum('amount'),
        ];

        return view('user.investments.index', compact('investments','totals'));
    }

    /**
     * Per-investment detail page.
     */
    public function show($id)
    {
        $user = Auth::user();
        $payment = Paymodel::where('user', $user->id)->where('id', $id)->firstOrFail();

        // FOM Licence Miner packages are not UVP/FC investments — their
        // details (escrow, installments, staking) live on the Licence Miner
        // escrow page instead.
        if ($payment->isFom()) {
            return redirect()->route('user.licence-miner.escrow')
                ->with('info', 'FOM Licence Miner packages are managed on the Licence Miner page, not under Investments.');
        }

        // FC VIP packages are not shown in the investments index, but the
        // user could still land on a direct /investments/{id} URL via
        // bookmarks/history. Send them to the FC Packages page instead.
        if ($payment->isFc()) {
            return redirect()->route('user.fc-packages')
                ->with('info', 'FC VIP memberships are managed on the FC Packages page.');
        }

        // Resolve the package model (Adventures or FCpackage) via the morph
        $package = null;
        if ($payment->payable_type && $payment->payable_id) {
            $package = $payment->payable_type::find($payment->payable_id);
        }
        // Fallback: look up by name
        if (!$package && $payment->category === 'VENTURE') {
            $package = Adventures::where('name', $payment->package)->first();
        }
        if (!$package && $payment->category === 'FC') {
            $package = FCpackage::where('name', $payment->package)->first();
        }

        $uvpPrice = \App\Models\TokenSetting::uvpPrice();
        $renewalPrice = \App\Models\TokenSetting::renewalPrice();

        // Locked tokens this investment granted
        $lockedTokens = $uvpPrice > 0 ? round($payment->amount / $uvpPrice, 4) : 0;

        // Renewals for this investment (10 per page; own page param so the
        // three paginated lists on this screen don't clash)
        $renewalsCount = PackageRenewal::where('payment_id', $payment->id)->count();
        $renewals = PackageRenewal::where('payment_id', $payment->id)
            ->orderBy('renewal_number')
            ->paginate(10, ['*'], 'renewals_page')
            ->withQueryString();

        // Daily income history for this investment
        // The daily_incomes table has: user_id, amount, earned_at, is_redeemed
        // (no payment_id column in the existing schema). To filter to a specific
        // investment, we pull the most recent 30 rows around the investment's
        // active window (created_at → expiration_date).
        $dailyIncomesQuery = DailyIncome::where('user_id', $user->id);
        if (\Illuminate\Support\Facades\Schema::hasColumn('daily_incomes', 'payment_id')) {
            $dailyIncomesQuery->where('payment_id', $payment->id);
        } else {
            $dailyIncomesQuery->whereBetween('earned_at', [
                Carbon::parse($payment->created_at)->toDateString(),
                $payment->expiration_date
                    ? Carbon::parse($payment->expiration_date)->toDateString()
                    : Carbon::now()->addDays(365)->toDateString(),
            ]);
        }
        $dailyIncomes = $dailyIncomesQuery->orderByDesc('earned_at')
            ->paginate(10, ['*'], 'incomes_page')
            ->withQueryString();

        // Cumulative base: sum of all income rows OLDER than the oldest row
        // on the current page, so the running total stays correct across pages.
        $oldestOnPage = collect($dailyIncomes->items())->last();
        $incomesCumulativeBase = 0.0;
        if ($oldestOnPage) {
            $baseQuery = DailyIncome::where('user_id', $user->id);
            if (\Illuminate\Support\Facades\Schema::hasColumn('daily_incomes', 'payment_id')) {
                $baseQuery->where('payment_id', $payment->id);
            } else {
                $baseQuery->whereBetween('earned_at', [
                    Carbon::parse($payment->created_at)->toDateString(),
                    $payment->expiration_date
                        ? Carbon::parse($payment->expiration_date)->toDateString()
                        : Carbon::now()->addDays(365)->toDateString(),
                ]);
            }
            $incomesCumulativeBase = (float) $baseQuery
                ->where(function ($q) use ($oldestOnPage) {
                    $q->where('earned_at', '<', $oldestOnPage->earned_at)
                      ->orWhere(function ($qq) use ($oldestOnPage) {
                          $qq->where('earned_at', $oldestOnPage->earned_at)
                             ->where('id', '<', $oldestOnPage->id);
                      });
                })
                ->sum('amount');
        }

        // Transactions tied to this investment (commissions, swap, renewals)
        // The transactions table uses JSON `transaction_details` — try multiple keys.
        $transactions = Transaction::where('user_id', $user->id)
            ->where(function ($q) use ($payment) {
                $q->whereJsonContains('transaction_details->source_payment_id', $payment->id)
                  ->orWhereJsonContains('transaction_details->payment_id', $payment->id)
                  ->orWhereJsonContains('transaction_details->package_id', $payment->id)
                  ->orWhere('transaction_no', (string) $payment->id);
            })
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'transactions_page')
            ->withQueryString();

        // Time-to-expiry + days elapsed
        $now = Carbon::now();
        $createdAt = Carbon::parse($payment->created_at);
        $expiresAt = $payment->expiration_date ? Carbon::parse($payment->expiration_date) : null;
        $daysElapsed = (int) $createdAt->diffInDays($now);
        $daysRemaining = $expiresAt ? max(0, (int) $now->diffInDays($expiresAt, false)) : null;

        // Compute max renewals
        $maxRenewals = $package && $package->duration ? (int) floor(($package->duration - 1) / 30) : 0;

        return view('user.investments.show', compact(
            'payment','package','uvpPrice','renewalPrice','lockedTokens',
            'renewals','renewalsCount','dailyIncomes','incomesCumulativeBase','transactions',
            'daysElapsed','daysRemaining','maxRenewals'
        ));
    }
}
