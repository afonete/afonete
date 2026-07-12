<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositWallet;
use App\Models\WithdrawalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function depositWallets()
    {
        // Group by type so the admin page can render 3 sections:
        // Crypto / Advcash / Perfect Money.
        $wallets = DepositWallet::orderBy('type')
                                ->orderBy('display_order')
                                ->orderBy('network')
                                ->get()
                                ->groupBy('type');
        return view('admin.settings.deposit-wallets', compact('wallets'));
    }

    public function updateDepositWallets(Request $request)
    {
        $data = $request->input('wallets', []);
        foreach ($data as $id => $row) {
            $w = DepositWallet::find($id);
            if (!$w) continue;
            $w->update([
                'wallet_address' => trim((string) ($row['wallet_address'] ?? '')),
                'label'          => $row['label'] ?? null,
                'min_amount'     => (float) ($row['min_amount'] ?? 10),
                'max_amount'     => ($row['max_amount'] ?? '') !== '' ? (float) $row['max_amount'] : null,
                'is_active'      => isset($row['is_active']) && $row['is_active'] === '1',
                'display_order'  => (int) ($row['display_order'] ?? 0),
                'instructions'   => $row['instructions'] ?? null,
                'notes'          => $row['notes'] ?? null,
            ]);
        }
        return back()->with('success', 'Deposit wallets updated.');
    }

    public function withdrawalSettings()
    {
        $settings = WithdrawalSetting::current();
        return view('admin.settings.withdrawal-settings', compact('settings'));
    }

    public function updateWithdrawalSettings(Request $request)
    {
        $settings = WithdrawalSetting::current();
        $coldWalletAddress = trim((string) $request->cold_wallet_address);
        $hotWalletAddress = trim((string) (config('services.tron.hot_wallet') ?: env('TRON_HOT_WALLET_ADDRESS')));
        $usdtContract = trim((string) (config('services.tron.usdt_contract') ?: env('TRON_USDT_CONTRACT')));
        $hotWalletMaxBalance = $request->hot_wallet_max_balance !== null && $request->hot_wallet_max_balance !== ''
            ? (float) $request->hot_wallet_max_balance
            : null;
        $hotWalletReserveBalance = (float) ($request->hot_wallet_reserve_balance ?? 100);

        if ($coldWalletAddress !== '' && !preg_match('/^T[a-zA-Z0-9]{33}$/', $coldWalletAddress)) {
            return back()->withInput()->with('error', 'Cold wallet must be a valid TRON address starting with T and 34 characters long.');
        }

        if ($coldWalletAddress !== '' && $hotWalletAddress !== '' && $coldWalletAddress === $hotWalletAddress) {
            return back()->withInput()->with('error', 'Cold wallet must not be the same as the hot wallet.');
        }

        if ($coldWalletAddress !== '' && $usdtContract !== '' && $coldWalletAddress === $usdtContract) {
            return back()->withInput()->with('error', 'Cold wallet must be a wallet address, not the USDT token contract address.');
        }

        if ($hotWalletReserveBalance < 0) {
            return back()->withInput()->with('error', 'Hot wallet reserve cannot be negative.');
        }

        if ($hotWalletMaxBalance !== null && $hotWalletMaxBalance > 0 && $hotWalletReserveBalance >= $hotWalletMaxBalance) {
            return back()->withInput()->with('error', 'Hot wallet reserve must be lower than hot wallet max USDT for automatic cold-wallet sweeps.');
        }

        $settings->update([
            'min_amount'             => (float) $request->min_amount,
            'min_deposit_amount'     => (float) ($request->min_deposit_amount ?? 10),
            'max_per_transaction'    => (float) $request->max_per_transaction,
            'daily_limit'            => $request->daily_limit ? (float) $request->daily_limit : null,
            'monthly_limit'          => $request->monthly_limit ? (float) $request->monthly_limit : null,
            'require_admin_approval'    => $request->has('require_admin_approval'),
            'auto_withdrawals_enabled'  => $request->has('auto_withdrawals_enabled'),
            'allow_free_dashboard_access'=> $request->has('allow_free_dashboard_access'),
            'admin_approval_threshold'  => (float) ($request->admin_approval_threshold ?? 100),
            'manual_review_risk_score'  => (int) ($request->manual_review_risk_score ?? 50),
            'max_auto_withdrawal'       => (float) ($request->max_auto_withdrawal ?? 100),
            'hot_wallet_max_balance'    => $hotWalletMaxBalance,
            'hot_wallet_reserve_balance'=> $hotWalletReserveBalance,
            'cold_wallet_address'       => $coldWalletAddress ?: null,
            'default_trc20_min_length'  => (int) $request->default_trc20_min_length,
            'validate_trc20_format'     => $request->has('validate_trc20_format'),
            'withdrawal_fee_percent'    => (float) ($request->withdrawal_fee_percent ?? 0.00),
            'notes'                  => $request->notes,
            'updated_by'             => Auth::id(),
        ]);
        return back()->with('success', 'Withdrawal & deposit settings updated.');
    }
}
