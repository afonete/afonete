<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TokenSetting;
use Illuminate\Support\Facades\Auth;

class TokenSettingController extends Controller
{
    public function index()
    {
        $setting = TokenSetting::first();
        return view('admin.token-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'uvp_price'     => 'required|numeric|min:0.000001',
            'renewal_price' => 'required|numeric|min:0.000001',
            'swap_price'    => 'required|numeric|min:0.000001',
            // trading_price and package_price are reserved for future modules
            'trading_price' => 'nullable|numeric|min:0.000001',
            'package_price' => 'nullable|numeric|min:0.000001',
            'coin_value'    => 'required|numeric|min:0.000001',
            'token_symbol'  => 'required|string|max:20',
            'notes'         => 'nullable|string|max:500',
        ]);

        // Default to current value if admin left the reserved fields blank
        $current = TokenSetting::first();

        $data = [
            'uvp_price'     => $request->uvp_price,
            'renewal_price' => $request->renewal_price,
            'swap_price'    => $request->swap_price,
            'trading_price' => $request->trading_price ?? ($current->trading_price ?? 0.0025),
            'package_price' => $request->package_price ?? ($current->package_price ?? 0.0025),
            'coin_value'    => $request->coin_value,
            'token_price'   => $request->uvp_price, // keep legacy field in sync
            'token_symbol'  => strtoupper($request->token_symbol),
            'notes'         => $request->notes,
            'updated_by'    => Auth::id(),
        ];

        $setting = TokenSetting::first();
        $setting ? $setting->update($data) : TokenSetting::create(array_merge($data, ['currency' => 'USD']));

        return redirect()->route('admin.token-settings')
            ->with('message', 'Token prices updated successfully.');
    }
}
