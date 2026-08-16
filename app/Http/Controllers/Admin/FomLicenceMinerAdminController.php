<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FomLicenceMiner;
use App\Models\TokenSetting;
use App\Models\Activations;
use App\Models\FomTokenInstallment;
use App\Models\FomTokenStaking;
use App\Models\ChartAccount;

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
     * Admin Report: Package Activation Codes & Redeemer Tracking.
     */
    public function codesReport()
    {
        $query = Activations::whereNotIn('package', ['TEAM_LEADER', 'SUPER_LEADER', 'TM']);

        if (method_exists(Activations::class, 'purchaser') && method_exists(Activations::class, 'redeemer')) {
            $query->with(['purchaser', 'redeemer']);
        } elseif (method_exists(Activations::class, 'myOwner')) {
            $query->with(['myOwner']);
        }

        $codes = $query->orderBy('id', 'desc')->paginate(20);

        $tokenSetting = TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        return view('admin.fom-licence-miner.codes', compact('codes', 'tokenSymbol'));
    }

    /**
     * Admin Report: Escrow Wallets & 1-5 Year Staking Audit.
     */
    public function escrowAuditReport()
    {
        FomTokenInstallment::ensureTable();
        FomTokenStaking::ensureTable();

        $installments = FomTokenInstallment::orderBy('id', 'desc')
            ->paginate(20, ['*'], 'installments_page');

        $stakings = FomTokenStaking::orderBy('id', 'desc')
            ->paginate(20, ['*'], 'stakings_page');

        $tokenSetting = TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        $totalEscrowInSystem = (float) ChartAccount::where('acc_type', 'ESCROW_TOKEN')->sum('amount');

        return view('admin.fom-licence-miner.escrow-audit', compact(
            'installments',
            'stakings',
            'tokenSymbol',
            'totalEscrowInSystem'
        ));
    }

    /**
     * Show form for creating a new FOM Licence Miner package.
     */
    public function create()
    {
        FomLicenceMiner::ensureSchema();

        $tokenSetting = TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        return view('admin.fom-licence-miner.create', compact('tokenSymbol'));
    }

    /**
     * Store a newly created FOM Licence Miner package in database.
     */
    public function store(Request $request)
    {
        FomLicenceMiner::ensureSchema();

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
            'volume_bonus'      => 'nullable|numeric|min:0',
            'token_symbol'      => 'nullable|string|max:50',
            'education_access'  => 'nullable|string|max:255',
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
            'volume_bonus'      => FomLicenceMiner::cleanNum($request->volume_bonus),
            'token_symbol'      => trim((string) $request->token_symbol) ?: null,
            'education_access'  => trim((string) $request->education_access) ?: 'Access to Education Courses',
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
        FomLicenceMiner::ensureSchema();

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
        FomLicenceMiner::ensureSchema();

        // Fail loudly if the schema could not be brought up to date
        // (e.g. DB user lacks ALTER privilege). Without this check the
        // UPDATE would throw a QueryException that the global handler
        // swallows into a silent redirect — "saved but nothing changed".
        if (!FomLicenceMiner::hasCurrentSchema()) {
            return back()->withInput()->with('error',
                'Database schema is out of date (missing token_symbol / volume_bonus / education_access columns) '
                . 'and could not be auto-updated. Please run: php artisan migrate');
        }

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
            'volume_bonus'      => 'nullable|numeric|min:0',
            'token_symbol'      => 'nullable|string|max:50',
            'education_access'  => 'nullable|string|max:255',
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
            'volume_bonus'      => FomLicenceMiner::cleanNum($request->volume_bonus),
            'token_symbol'      => trim((string) $request->token_symbol) ?: null,
            'education_access'  => trim((string) $request->education_access) ?: 'Access to Education Courses',
            'unlocked_per_week' => $request->unlocked_per_week ?: 'YES',
            'allowed_loan'      => $request->allowed_loan ?: '',
            'investment_option' => $request->investment_option ?: '',
            'total_return'      => FomLicenceMiner::cleanNum($request->total_return),
            'sort_order'        => (int) ($request->sort_order ?: $package->sort_order),
            'is_active'         => $request->has('is_active') ? true : false,
        ];

        try {
            $package->update($data);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("FomLicenceMiner update failed for #{$id}: " . $e->getMessage());
            return back()->withInput()->with('error',
                'Saving the package failed: ' . $e->getMessage());
        }

        // Read-back verification: confirm the four editable config fields
        // physically landed in the database before reporting success.
        $saved = FomLicenceMiner::find($id);
        $expectedSymbol = trim((string) $request->token_symbol) ?: null;
        $persisted = $saved
            && (string) $saved->token_symbol === (string) $expectedSymbol
            && FomLicenceMiner::cleanNum($saved->volume_point) == FomLicenceMiner::cleanNum($request->volume_point)
            && FomLicenceMiner::cleanNum($saved->volume_bonus) == FomLicenceMiner::cleanNum($request->volume_bonus)
            && (string) $saved->education_access === (string) $data['education_access'];

        if (!$persisted) {
            \Illuminate\Support\Facades\Log::error("FomLicenceMiner update for #{$id} did not persist as submitted.", [
                'expected' => [
                    'token_symbol'     => $expectedSymbol,
                    'volume_point'     => FomLicenceMiner::cleanNum($request->volume_point),
                    'volume_bonus'     => FomLicenceMiner::cleanNum($request->volume_bonus),
                    'education_access' => $data['education_access'],
                ],
                'actual' => $saved ? $saved->only(['token_symbol', 'volume_point', 'volume_bonus', 'education_access']) : null,
            ]);
            return back()->withInput()->with('error',
                'The package was saved but one or more fields did not persist correctly. Check the application log.');
        }

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
