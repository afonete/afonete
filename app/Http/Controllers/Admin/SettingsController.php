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
            'hot_wallet_max_balance'    => $request->hot_wallet_max_balance !== null && $request->hot_wallet_max_balance !== '' ? (float) $request->hot_wallet_max_balance : null,
            'hot_wallet_reserve_balance'=> (float) ($request->hot_wallet_reserve_balance ?? 100),
            'cold_wallet_address'       => trim((string) $request->cold_wallet_address) ?: null,
            'default_trc20_min_length'  => (int) $request->default_trc20_min_length,
            'validate_trc20_format'     => $request->has('validate_trc20_format'),
            'notes'                  => $request->notes,
            'updated_by'             => Auth::id(),
        ]);
        return back()->with('success', 'Withdrawal & deposit settings updated.');
    }
}
