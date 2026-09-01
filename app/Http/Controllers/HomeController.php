<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Contacted;
use App\Models\requested;
use App\Models\Balance;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        return view('home.welcome');
    }

    public function about(){
        return view('home.about');
    }

    public function contact(){
        return view('home.contact');
    }

    public function contacted(Request $request){
        $name=$request->name;
        $sur=$request->sur;
        $email=$request->email;
        $phone=$request->phone;
        $message=$request->message;

        $contacted= new Contacted();
        $contacted->name=$name;
        $contacted->sur=$sur;
        $contacted->phone=$phone;
        $contacted->email=$email;
        $contacted->message=$message;

        if ($contacted->save()) {
            return view('home.contact')->with('message','success');
        } else {
            return view('home.contact')->with('message','error');
        }
    }

    public function project(){
        return view('home.project');
    }

    public function investmentPackage(){
        \App\Models\FomLicenceMiner::ensureTableAndData();
        $packages = \App\Models\FomLicenceMiner::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $tokenSetting = \App\Models\TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        $user = \Illuminate\Support\Facades\Auth::user();
        $depositWalletBalance = 0.0;
        if ($user) {
            $depositWalletBalance = (float) $user->ChartAccount()->where('acc_type', 'DEPOSIT')->sum('amount');
            \App\Models\FomTokenInstallment::processDueInstallments($user);
            \App\Models\FomTokenStaking::processDueStakings($user);
        }

        return view('user.investment-package', compact('packages', 'tokenSymbol', 'depositWalletBalance'));
    }

    public function escrowWalletDetails()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Process any due monthly installments & due 1-5 year stakings
        \App\Models\FomTokenInstallment::processDueInstallments($user);
        \App\Models\FomTokenStaking::processDueStakings($user);

        // Fetch balances
        $escrowBal    = (float) $user->ChartAccount()->where('acc_type', 'ESCROW_TOKEN')->sum('amount');
        $availableBal = (float) $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');
        $freeBal      = (float) $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
        $tokenSymbol  = \App\Models\TokenSetting::currentSymbol();

        // Fetch 12 Monthly Installment Schedules
        \App\Models\FomTokenInstallment::ensureTable();
        $installments = \App\Models\FomTokenInstallment::where('user_id', $user->id)
            ->orderBy('release_date', 'asc')
            ->get();

        // ── Group installments per activated package for per-package cards ──
        // Group key: activation_id when present, otherwise package_name
        // (legacy rows created before activation_id was recorded).
        $packageGroups = $installments
            ->groupBy(function ($inst) {
                return $inst->activation_id ? 'act-' . $inst->activation_id : 'pkg-' . strtoupper((string) $inst->package_name);
            })
            ->map(function ($group) {
                $first     = $group->first();
                $completed = $group->where('status', 'completed');
                $pending   = $group->where('status', 'pending');
                $next      = $pending->sortBy('release_date')->first();

                return (object) [
                    'activation_id'   => $first->activation_id,
                    'package_name'    => $first->package_name,
                    'symbol'          => \App\Models\FomLicenceMiner::symbolForPackageName($first->package_name),
                    'total_return'    => (float) $group->sum('amount'),
                    'released_tokens' => (float) $completed->sum('amount'),
                    'pending_tokens'  => (float) $pending->sum('amount'),
                    'released_count'  => $completed->count(),
                    'total_count'     => $group->count(),
                    'next_release'    => $next ? $next->release_date : null,
                    'started_at'      => $group->min('created_at'),
                    'is_complete'     => $pending->isEmpty(),
                ];
            })
            ->sortByDesc('started_at')
            ->values();

        // Fetch 1-5 Year Staking Records
        \App\Models\FomTokenStaking::ensureTable();
        $stakings = \App\Models\FomTokenStaking::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        // Admin yield percentages for 1 to 5 years
        $y1 = \App\Models\FomTokenStaking::getYieldPercent(1);
        $y2 = \App\Models\FomTokenStaking::getYieldPercent(2);
        $y3 = \App\Models\FomTokenStaking::getYieldPercent(3);
        $y4 = \App\Models\FomTokenStaking::getYieldPercent(4);
        $y5 = \App\Models\FomTokenStaking::getYieldPercent(5);

        return view('user.escrow-wallet-details', compact(
            'escrowBal',
            'availableBal',
            'freeBal',
            'tokenSymbol',
            'installments',
            'packageGroups',
            'stakings',
            'y1', 'y2', 'y3', 'y4', 'y5'
        ));
    }

    /**
     * Per-package escrow detail page: the 12-month release schedule and
     * summary for ONE activated FOM Licence Miner package.
     *
     * $key is either an activation id (numeric) or a package name slug for
     * legacy installments that were created without an activation_id.
     */
    public function escrowPackageDetails($key)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Release anything due before showing the schedule.
        \App\Models\FomTokenInstallment::processDueInstallments($user);

        \App\Models\FomTokenInstallment::ensureTable();

        // Scope STRICTLY to this user — a foreign activation id must 404-fallback.
        $query = \App\Models\FomTokenInstallment::where('user_id', $user->id);

        if (ctype_digit((string) $key)) {
            $query->where('activation_id', (int) $key);
        } else {
            $query->whereNull('activation_id')
                  ->whereRaw('UPPER(package_name) = ?', [strtoupper(trim((string) $key))]);
        }

        $installments = $query->orderBy('release_date', 'asc')->get();

        if ($installments->isEmpty()) {
            return redirect()->route('user.licence-miner.escrow')
                ->with('error', 'No escrow schedule found for that package on your account.');
        }

        $first     = $installments->first();
        $completed = $installments->where('status', 'completed');
        $pending   = $installments->where('status', 'pending');
        $next      = $pending->sortBy('release_date')->first();

        $packageName = $first->package_name;
        $pkg         = \App\Models\FomLicenceMiner::whereRaw('UPPER(name) = ?', [strtoupper((string) $packageName)])->first();
        $symbol      = $pkg ? $pkg->effectiveTokenSymbol() : \App\Models\TokenSetting::currentSymbol();

        $summary = (object) [
            'package_name'    => $packageName,
            'activation_id'   => $first->activation_id,
            'symbol'          => $symbol,
            'total_return'    => (float) $installments->sum('amount'),
            'released_tokens' => (float) $completed->sum('amount'),
            'pending_tokens'  => (float) $pending->sum('amount'),
            'released_count'  => $completed->count(),
            'total_count'     => $installments->count(),
            'next_release'    => $next ? $next->release_date : null,
            'started_at'      => $installments->min('created_at'),
            'is_complete'     => $pending->isEmpty(),
        ];

        return view('user.escrow-package-details', compact('installments', 'summary', 'pkg', 'symbol'));
    }

    public function buyFomPackage(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to purchase a package.');
        }

        $request->validate([
            'package_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        // Only active FOM packages are purchasable (admin can deactivate a
        // package; a direct POST with its id must not bypass that).
        $package = \App\Models\FomLicenceMiner::where('id', $request->package_id)
            ->where('is_active', true)
            ->firstOrFail();
        $quantity = (int) $request->quantity;

        $unitPrice   = \App\Models\FomLicenceMiner::cleanNum($package->price);
        $totalCost   = $unitPrice * $quantity;
        $unitReturn  = \App\Models\FomLicenceMiner::cleanNum($package->total_return);
        $totalReturn = $unitReturn * $quantity;

        // Pre-check Deposit Wallet Balance (authoritative check happens inside
        // the transaction with the row locked).
        $depositBalance = (float) $user->ChartAccount()->where('acc_type', 'DEPOSIT')->sum('amount');
        if ($depositBalance < $totalCost) {
            return back()->with('error', "Insufficient Deposit Wallet balance ($" . number_format($depositBalance, 2) . "). You need $" . number_format($totalCost, 2) . " to purchase {$quantity}x {$package->name} package(s). Please deposit funds into your Deposit Wallet first.");
        }

        // Verify Second Transaction Password if set
        if (!empty($user->transaction_password)) {
            $txPassword = (string) $request->input('transaction_password', '');
            if (empty($txPassword) || !\Illuminate\Support\Facades\Hash::check($txPassword, $user->transaction_password)) {
                return back()->with('error', 'Invalid Second Transaction Password. Please enter your correct transaction password.');
            }
        }

        // Atomic purchase: debit + code generation + transaction log all
        // commit together, or none of them do. The DEPOSIT row is locked
        // (SELECT ... FOR UPDATE) so concurrent purchases cannot double-spend.
        $createdCodes = [];
        $firstActivation = null;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($user, $package, $quantity, $unitPrice, $unitReturn, $totalCost, $totalReturn, &$createdCodes, &$firstActivation) {
                // Debit Deposit Wallet (strict: throws on insufficient funds,
                // rolling back the entire purchase).
                \App\Models\ChartAccount::debitLocked(
                    $user->id,
                    'DEPOSIT',
                    $totalCost,
                    true,
                    "FOM package purchase {$quantity}x {$package->name}"
                );

                // Generate Individual Activation Codes per unit
                for ($i = 0; $i < $quantity; $i++) {
                    $code = 'FOM-' . strtoupper(\Illuminate\Support\Str::random(13));
                    $act = \App\Models\Activations::create([
                        'code'     => $code,
                        'package'  => $package->name,
                        'stutus'   => 'not',
                        'price'    => $unitPrice,
                        'token'    => $unitReturn,
                        'email'    => $user->email,
                        'user_id'  => $user->id,
                        'period'   => ($package->duration_days ?: 600) . ' Days',
                    ]);
                    $createdCodes[] = [
                        'id'   => $act->id,
                        'code' => $code,
                    ];
                    if ($i === 0) {
                        $firstActivation = $act;
                    }
                }

                // Log transaction
                $txnNo = class_exists(\App\Models\Transaction::class) && method_exists(\App\Models\Transaction::class, 'generateTransactionNo')
                    ? \App\Models\Transaction::generateTransactionNo()
                    : 'FOM-BUY-' . time() . '-' . rand(100, 999);

                \App\Models\Transaction::create([
                    'user_id'             => $user->id,
                    'transaction_no'      => $txnNo,
                    'transaction_type'    => 'FOM_PACKAGE_PURCHASE',
                    'transaction_details' => json_encode([
                        'package'      => $package->name,
                        'quantity'     => $quantity,
                        'total_cost'   => $totalCost,
                        'total_return' => $totalReturn,
                        'codes'        => array_column($createdCodes, 'code'),
                        'paid_via'     => 'DEPOSIT_WALLET',
                    ]),
                ]);

                // Record the spend in the Deposits ledger too (status 'used'),
                // exactly like UVP purchases do. Without this row the ledger
                // formula (approved − used) keeps reporting the TOTAL deposit
                // as "available" and the wallet-page ChartAccount sync would
                // silently restore the spent funds.
                $lastDeposit = \App\Models\Deposits::where('user_id', $user->id)
                    ->whereNotNull('user_wallet_address')
                    ->latest()
                    ->first();

                \App\Models\Deposits::create([
                    'user_id'             => $user->id,
                    'amount_deposited'    => 0,
                    'amount_removed'      => $totalCost,
                    'currency_type'       => 'DOLLAR',
                    'deposit_method'      => 'FOM_PACKAGE_PURCHASE',
                    'user_wallet_address' => ($lastDeposit && !empty($lastDeposit->user_wallet_address)) ? $lastDeposit->user_wallet_address : 'INTERNAL_DEPOSIT_WALLET',
                    'network'             => ($lastDeposit && !empty($lastDeposit->network)) ? $lastDeposit->network : 'TRC-20',
                    'status'              => 'used',
                    'transaction_id'      => $txnNo,
                    'comment'             => "FOM package purchase {$quantity}x {$package->name}",
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', "Insufficient Deposit Wallet balance. You need $" . number_format($totalCost, 2) . " to purchase {$quantity}x {$package->name} package(s). Please deposit funds into your Deposit Wallet first.");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('buyFomPackage failed: ' . $e->getMessage());
            return back()->with('error', 'Purchase failed and no funds were deducted. Please try again.');
        }

        return back()->with('purchase_success', true)
            ->with('created_codes', $createdCodes)
            ->with('activation_code', $firstActivation->code ?? '')
            ->with('activation_id', $firstActivation->id ?? 0)
            ->with('package_name', $package->name)
            ->with('quantity', $quantity)
            ->with('total_cost', $totalCost)
            ->with('total_return', $totalReturn);
    }

    public function activateFomCode(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to activate your package.');
        }

        $code = trim((string)$request->code);
        $activationId = $request->activation_id;

        $activation = null;
        if ($activationId) {
            $activation = \App\Models\Activations::where('id', $activationId)
                ->whereIn('stutus', ['not', 'pending'])
                ->first();
        }
        if (!$activation && $code !== '') {
            $activation = \App\Models\Activations::where('code', $code)
                ->whereIn('stutus', ['not', 'pending'])
                ->first();
        }

        if (!$activation) {
            return back()->with('error', 'Invalid or already activated code.');
        }

        // Hard guard: this endpoint activates FOM Licence Miner codes ONLY.
        // UVP / FC / Leader codes must go through their own activation flow —
        // otherwise a UVP code would wrongly credit escrow tokens and create
        // a FOM installment schedule.
        if (!\App\Models\FomLicenceMiner::isFomActivation($activation)) {
            return back()->with('error', 'This code is not a FOM Licence Miner code. Please activate it from the Activation page instead.');
        }

        // Check if user has ALREADY activated an active package with this EXACT package name
        $alreadyActivatedSamePackage = \App\Models\Payment::where('user', $user->id)
            ->where('package', $activation->package)
            ->where('is_expired', false)
            ->exists() || (strtoupper((string)$user->has_paid_package) === strtoupper((string)$activation->package));

        if ($alreadyActivatedSamePackage) {
            return back()->with('error', "You have already activated a {$activation->package} package on your account. You cannot activate a second {$activation->package} package. You can copy this activation code ({$activation->code}) and give it to another user to activate on their account!");
        }

        $totalReturn = (float) $activation->token;
        if ($totalReturn <= 0) {
            $fomPkg = \App\Models\FomLicenceMiner::where('name', $activation->package)->first();
            if ($fomPkg) {
                $totalReturn = \App\Models\FomLicenceMiner::cleanNum($fomPkg->total_return);
            }
        }

        // Atomic activation: code redemption + payment record + escrow credit
        // + installment schedule all commit together, or none of them do.
        // The activation row is locked (SELECT ... FOR UPDATE) and its status
        // re-checked, so the same code can never be redeemed twice.
        try {
            $fomPayment = null;
            \Illuminate\Support\Facades\DB::transaction(function () use ($user, $activation, $totalReturn, &$fomPayment) {
                // Re-fetch and lock the code; abort if it was consumed by a
                // concurrent request in the meantime.
                $locked = \App\Models\Activations::where('id', $activation->id)
                    ->lockForUpdate()
                    ->first();
                if (!$locked || !in_array($locked->stutus, ['not', 'pending'])) {
                    throw new \RuntimeException('CODE_ALREADY_USED');
                }

                // Mark code as used
                $locked->stutus = 'used';
                $locked->email = $user->email;
                $locked->save();

                // Update user package status & record Payment
                $user->has_paid_package = $locked->package;
                $user->has_free_package = 'no';
                $user->save();

                $fomPayment = \App\Models\Payment::create([
                    'user'            => $user->id,
                    'package'         => $locked->package,
                    'amount'          => $locked->price,
                    'paid'            => $locked->price,
                    'over_paid'       => 0,
                    'status'          => 1,
                    'is_expired'      => false,
                    'duration'        => 600,
                    'category'        => $locked->package ?: 'FOM',
                    'category_id'     => 1,
                    'payable_type'    => \App\Models\Activations::class,
                    'payable_id'      => $locked->id,
                    'expiration_date' => \Carbon\Carbon::now()->addDays(600)->toDateTimeString(),
                ]);

                // Credit Independent Escrow Wallet (ESCROW_TOKEN) with row lock
                \App\Models\ChartAccount::creditLocked(
                    $user->id,
                    'ESCROW_TOKEN',
                    $totalReturn,
                    "FOM activation code {$locked->code} ({$locked->package})"
                );

                // Create 12 Monthly Installment Schedule
                \App\Models\FomTokenInstallment::createSchedule($user->id, $locked->id, $locked->package, $totalReturn);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'CODE_ALREADY_USED') {
                return back()->with('error', 'Invalid or already activated code.');
            }
            \Illuminate\Support\Facades\Log::error('activateFomCode failed: ' . $e->getMessage());
            return back()->with('error', 'Activation failed and nothing was changed. Please try again.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('activateFomCode failed: ' . $e->getMessage());
            return back()->with('error', 'Activation failed and nothing was changed. Please try again.');
        }

        // FOM 10-level referral commissions (after the activation commit;
        // idempotent per referrer+payment+level)
        if ($fomPayment) {
            \App\Services\FomReferralService::creditForFomPurchase($fomPayment);
            \App\Services\FomIncentiveService::onFomActivation($fomPayment);
        }

        // Process any due installments (outside the activation transaction —
        // each installment release is itself atomic)
        \App\Models\FomTokenInstallment::processDueInstallments($user);

        $tokenSetting = \App\Models\TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        return back()->with('success', "Package '{$activation->package}' activated successfully! " . number_format($totalReturn) . " {$tokenSymbol} credited to your Escrow Wallet. It will be released in 12 monthly installments into your Available Token balance.");
    }

    public function stakerPackage(){
        // §96: Staker Package is parked under Legacy / Coming Soon — the
        // page must not be accessible (direct URL included) until it is
        // re-launched. To re-enable: remove this redirect.
        return redirect()->route('user.dashboard')
            ->with('message', 'Staker Package is coming soon. Stay tuned!');

        return view('user.staker-package');
    }

    public function privacy(){
        return view('terms.privacy');
    }

    public function condition(){
        return view('terms.condition');
    }

    public function info(){
        return view('terms.getInfo');
    }

    public function error(){
        return view('user.user-package')->with('back','payment canceled, not confirmed');
    }

    public function try() {
        return view('user.coin');
    }

    public function requested() {
        return view('requested');
    }

    public function request(Request $request) {
        $email=$request->email;
        $name=$request->name;
        $phone=$request->phone;
        $referee=$request->referee_id;
        $username=$request->user;
        $country=$request->country;

        $requested= new requested();
        $requested->name=$name;
        $requested->email=$email;
        $requested->phone=$phone;
        $requested->status='pending';

        if ($requested->save()){
            return view('auth.register')->with('request','Thank u for requesting to become Bifonex member, You will be contacted very soon');
        } else {
            return view('auth.register')->with('request','request failed, -->consult live chat');
        }
    }

    public function balance() {
        $pay=DB::SELECT('SELECT * from users where activation=1874089 limit 1');
        if (count($pay)) {
            foreach($pay as $payee) {
                $payer=$payee->user;
                $paybalance=new Balance();
                $paybalance->user=$payer;
                $paybalance->reserved_token=500;

                if ($paybalance->save()):
                    return ('saved');
                endif;
                return ('failed');
            }
        }
    }
}
