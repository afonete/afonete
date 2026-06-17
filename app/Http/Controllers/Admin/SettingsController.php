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
        $wallets = DepositWallet::orderBy('network')->get();
        return view('admin.settings.deposit-wallets', compact('wallets'));
    }

    public function updateDepositWallets(Request $request)
    {
        foreach ($request->input('wallets', []) as $id => $data) {
            $w = DepositWallet::find($id);
            if (!$w) continue;
            $w->update([
                'wallet_address' => $data['wallet_address'],
                'label'          => $data['label'],
                'min_amount'     => (float) ($data['min_amount'] ?? 10),
                'max_amount'     => $data['max_amount'] !== null ? (float) $data['max_amount'] : null,
                'is_active'      => isset($data['is_active']),
                'notes'          => $data['notes'] ?? null,
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
            'max_per_transaction'    => (float) $request->max_per_transaction,
            'daily_limit'            => $request->daily_limit ? (float) $request->daily_limit : null,
            'monthly_limit'          => $request->monthly_limit ? (float) $request->monthly_limit : null,
            'require_admin_approval' => $request->has('require_admin_approval'),
            'default_trc20_min_length'=> (int) $request->default_trc20_min_length,
            'validate_trc20_format'  => $request->has('validate_trc20_format'),
            'notes'                  => $request->notes,
            'updated_by'             => Auth::id(),
        ]);
        return back()->with('success', 'Withdrawal settings updated.');
    }
}
