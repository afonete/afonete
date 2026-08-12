<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FomLicenceMiner;
use App\Models\TokenSetting;

class FomLicenceMinerAdminController extends Controller
{
    /**
     * Display a listing of FOM Licence Miner packages.
     */
    public function index()
    {
        FomLicenceMiner::ensureTableAndData();

        $packages = FomLicenceMiner::orderBy('sort_order')->orderBy('id')->get();
        $tokenSetting = TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        return view('admin.fom-licence-miner.index', compact('packages', 'tokenSetting', 'tokenSymbol'));
    }

    /**
     * Show form for creating a new FOM Licence Miner package.
     */
    public function create()
    {
        $tokenSetting = TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        return view('admin.fom-licence-miner.create', compact('tokenSymbol'));
    }

    /**
     * Store a newly created FOM Licence Miner package in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:100',
            'price'             => 'required|numeric|min:0',
            'display_price'     => 'nullable|string|max:100',
            'tokens'            => 'required|numeric|min:0',
            'duration_days'     => 'required|integer|min:1',
            'token_bonus'       => 'nullable|numeric|min:0',
            'direct_sponsors'   => 'nullable|numeric|min:0',
            'affiliate_vbonus'  => 'nullable|numeric|min:0',
            'space_shop_limit'  => 'nullable|string|max:100',
            'volume_point'      => 'nullable|numeric|min:0',
            'unlocked_per_week' => 'nullable|string|max:50',
            'allowed_loan'      => 'nullable|string|max:100',
            'investment_option' => 'nullable|string|max:100',
            'total_return'      => 'nullable|numeric|min:0',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
        ]);

        $data = [
            'name'              => trim($request->name),
            'price'             => FomLicenceMiner::cleanNum($request->price),
            'display_price'     => $request->display_price ?: '',
            'tokens'            => FomLicenceMiner::cleanNum($request->tokens),
            'duration_days'     => (int) ($request->duration_days ?: 600),
            'token_bonus'       => FomLicenceMiner::cleanNum($request->token_bonus),
            'direct_sponsors'   => FomLicenceMiner::cleanNum($request->direct_sponsors),
            'affiliate_vbonus'  => FomLicenceMiner::cleanNum($request->affiliate_vbonus),
            'space_shop_limit'  => $request->space_shop_limit ?: 'Space Shop Room Limit',
            'volume_point'      => FomLicenceMiner::cleanNum($request->volume_point),
            'unlocked_per_week' => $request->unlocked_per_week ?: 'YES',
            'allowed_loan'      => $request->allowed_loan ?: '',
            'investment_option' => $request->investment_option ?: '',
            'total_return'      => FomLicenceMiner::cleanNum($request->total_return),
            'sort_order'        => (int) ($request->sort_order ?: 0),
            'is_active'         => $request->has('is_active') ? true : false,
        ];

        FomLicenceMiner::create($data);

        return redirect()->route('admin.fom-licence-miner.index')
            ->with('message', 'FOM Licence Miner package created successfully!');
    }

    /**
     * Show form for editing an existing package.
     */
    public function edit($id)
    {
        $package = FomLicenceMiner::findOrFail($id);
        $tokenSetting = TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        return view('admin.fom-licence-miner.edit', compact('package', 'tokenSymbol'));
    }

    /**
     * Update an existing package in database.
     */
    public function update(Request $request, $id)
    {
        $package = FomLicenceMiner::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:100',
            'price'             => 'required|numeric|min:0',
            'display_price'     => 'nullable|string|max:100',
            'tokens'            => 'required|numeric|min:0',
            'duration_days'     => 'required|integer|min:1',
            'token_bonus'       => 'nullable|numeric|min:0',
            'direct_sponsors'   => 'nullable|numeric|min:0',
            'affiliate_vbonus'  => 'nullable|numeric|min:0',
            'space_shop_limit'  => 'nullable|string|max:100',
            'volume_point'      => 'nullable|numeric|min:0',
            'unlocked_per_week' => 'nullable|string|max:50',
            'allowed_loan'      => 'nullable|string|max:100',
            'investment_option' => 'nullable|string|max:100',
            'total_return'      => 'nullable|numeric|min:0',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
        ]);

        $data = [
            'name'              => trim($request->name),
            'price'             => FomLicenceMiner::cleanNum($request->price),
            'display_price'     => $request->display_price ?: '',
            'tokens'            => FomLicenceMiner::cleanNum($request->tokens),
            'duration_days'     => (int) ($request->duration_days ?: $package->duration_days),
            'token_bonus'       => FomLicenceMiner::cleanNum($request->token_bonus),
            'direct_sponsors'   => FomLicenceMiner::cleanNum($request->direct_sponsors),
            'affiliate_vbonus'  => FomLicenceMiner::cleanNum($request->affiliate_vbonus),
            'space_shop_limit'  => $request->space_shop_limit ?: 'Space Shop Room Limit',
            'volume_point'      => FomLicenceMiner::cleanNum($request->volume_point),
            'unlocked_per_week' => $request->unlocked_per_week ?: 'YES',
            'allowed_loan'      => $request->allowed_loan ?: '',
            'investment_option' => $request->investment_option ?: '',
            'total_return'      => FomLicenceMiner::cleanNum($request->total_return),
            'sort_order'        => (int) ($request->sort_order ?: $package->sort_order),
            'is_active'         => $request->has('is_active') ? true : false,
        ];

        $package->update($data);

        return redirect()->route('admin.fom-licence-miner.index')
            ->with('message', 'FOM Licence Miner package updated successfully!');
    }

    /**
     * Toggle status (active/inactive).
     */
    public function toggle($id)
    {
        $package = FomLicenceMiner::findOrFail($id);
        $package->is_active = !$package->is_active;
        $package->save();

        return back()->with('message', 'Package status updated successfully!');
    }

    /**
     * Remove a package.
     */
    public function destroy($id)
    {
        $package = FomLicenceMiner::findOrFail($id);
        $package->delete();

        return back()->with('message', 'FOM Licence Miner package deleted successfully!');
    }
}
