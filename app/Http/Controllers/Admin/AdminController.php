<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Models\Deposits;

use App\Mail\reminder;
use App\Mail\SendDepositApproval;
use App\Mail\CancelDeposit;
use App\Mail\RejectDeposit;
use App\Models\Activations;
use App\Models\ChartAccount;
use App\Models\Transaction;
use App\Models\withdrawals as WithdrawalModel;

use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{


    // ════════════════════════════════════════════════════════════
    // METHOD 1: Withdrawal()  (line 28) — with ?method= filter
    // ════════════════════════════════════════════════════════════
    public function Withdrawal(Request $request)
    {
        // Optional ?method=crypto|advcash|perfect_money filter for the admin queue.
        $methodFilter = $request->query('method');

        $pendingQ    = WithdrawalModel::where('status', 'pending')->with('user')->latest();
        $processingQ = WithdrawalModel::where('status', 'processing')->with('user')->latest();
        $completedQ  = WithdrawalModel::where('status', 'completed')->with('user')->latest();

        if ($methodFilter && in_array($methodFilter, ['crypto', 'advcash', 'perfect_money'], true)) {
            $pendingQ    = $pendingQ->where('method', $methodFilter);
            $processingQ = $processingQ->where('method', $methodFilter);
            $completedQ  = $completedQ->where('method', $methodFilter);
        }

        return view('admin.Withdrawal', [
            'pending'      => $pendingQ->get(),
            'processing'   => $processingQ->get(),
            'completed'    => $completedQ->paginate(20)->withQueryString(),
            'methodFilter' => $methodFilter,
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // METHOD 2: approveWithdrawal()  (line 51) — stamps processed_by/at
    // ════════════════════════════════════════════════════════════
    public function approveWithdrawal(Request $request)
    {
        $withdrawal = WithdrawalModel::findOrFail($request->withdrawal_id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->route('admin.withdrawal')->with('error', 'This withdrawal has already been processed.');
        }

        $txnHash = trim((string) $request->txn_hash);
        $canAutoSend = $withdrawal->method === WithdrawalModel::METHOD_CRYPTO
            && strtoupper((string) $withdrawal->currency) === 'USDT'
            && strtoupper((string) $withdrawal->network) === 'TRC-20'
            && $txnHash === ''
            && (bool) env('DIRECT_BLOCKCHAIN_WITHDRAWALS', true);

        if ($canAutoSend) {
            // Admin approval releases the held withdrawal into the blockchain queue.
            $withdrawal->update([
                'status'            => WithdrawalModel::STATUS_PROCESSING,
                'approval_required' => false,
                'admin_note'        => $request->admin_note ?: 'Approved by admin for automatic blockchain payout.',
                'processed_by'      => Auth::id(),
                'processed_at'      => now(),
            ]);

            \App\Models\BlockchainAuditLog::record('withdrawal.admin_approved_queued', [
                'user_id'        => $withdrawal->user_id,
                'auditable_type' => WithdrawalModel::class,
                'auditable_id'   => $withdrawal->id,
                'address'        => $withdrawal->wallet_address,
                'amount'         => $withdrawal->amount,
                'currency'       => $withdrawal->currency,
                'network'        => $withdrawal->network,
                'request_id'     => $withdrawal->transaction_no,
                'message'        => 'Admin approved withdrawal and queued automatic blockchain payout.',
            ]);

            \App\Jobs\ProcessBlockchainWithdrawal::dispatch($withdrawal->id);
            return redirect()->route('admin.withdrawal')->with('message', 'Withdrawal approved and queued for automatic blockchain payout.');
        }

        // Manual approval path: admin has already sent funds externally and provides tx hash/reference.
        $withdrawal->update([
            'status'             => 'completed',
            'admin_note'         => $request->admin_note,
            'txn_hash'           => $txnHash ?: null,
            'blockchain_tx_hash' => $txnHash ?: $withdrawal->blockchain_tx_hash,
            'processed_by'       => Auth::id(),
            'processed_at'       => now(),
        ]);

        \App\Models\BlockchainAuditLog::record('withdrawal.admin_marked_completed', [
            'user_id'        => $withdrawal->user_id,
            'auditable_type' => WithdrawalModel::class,
            'auditable_id'   => $withdrawal->id,
            'tx_hash'        => $txnHash ?: null,
            'address'        => $withdrawal->wallet_address,
            'amount'         => $withdrawal->amount,
            'currency'       => $withdrawal->currency,
            'network'        => $withdrawal->network,
            'request_id'     => $withdrawal->transaction_no,
            'message'        => 'Admin manually marked withdrawal completed.',
        ]);

        // FIX (W6): Email user about approval
        $user = User::find($withdrawal->user_id);
        if ($user) {
            try {
                \Mail::to($user->email)->send(new \App\Mail\WithdrawalApproved(
                    $user, $withdrawal->amount, $withdrawal->transaction_no, $txnHash
                ));
            } catch (\Exception $e) {
                \Log::warning('Withdrawal approval email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.withdrawal')->with('message', 'Withdrawal approved and marked completed.');
    }

    // ════════════════════════════════════════════════════════════
    // METHOD 3: rejectWithdrawal()  (line 84) — stamps processed_by/at + refunds
    // ════════════════════════════════════════════════════════════
    public function rejectWithdrawal(Request $request)
    {
        $withdrawal = WithdrawalModel::findOrFail($request->withdrawal_id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->route('admin.withdrawal')->with('error', 'This withdrawal has already been processed.');
        }

        $user = User::find($withdrawal->user_id);

        // ── Refund the amount back to CASHOUT ──
        if ($user) {
            $existing = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
                ['amount'  => $existing + $withdrawal->amount]
            );

            // Log refund transaction
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => Transaction::generateTransactionNo(),
                'transaction_type'    => 'WITHDRAWAL_REFUND',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'amount'     => $withdrawal->amount,
                    'reason'     => $request->admin_note ?? 'Rejected by admin',
                    'date'       => now()->toDateTimeString(),
                    'status'     => 'refunded',
                    'username'   => $user->name,
                ]),
            ]);
        }

        $withdrawal->update([
            'status'       => 'failed',
            'admin_note'   => $request->admin_note ?? 'Rejected by admin',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        // FIX (W6): Email user about rejection
        if ($user) {
            try {
                \Mail::to($user->email)->send(new \App\Mail\WithdrawalRejected(
                    $user, $withdrawal->amount, $withdrawal->transaction_no, $request->admin_note ?? 'No reason provided'
                ));
            } catch (\Exception $e) {
                \Log::warning('Withdrawal rejection email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.withdrawal')->with('message', 'Withdrawal rejected and balance refunded to user.');
    }
    public function users(){
        $users = User::where('utype', '!=', 'ADM')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users-list',["users"=>$users]);
    }

    public function depositedPayment(){
        // Paginate 10 per page, newest first. Eager-load user to avoid N+1 queries.
        $deposits = Deposits::with('user')
            ->where('status', '!=', 'used')
            ->latest('created_at')
            ->paginate(10);

        return view('admin.payments', compact('deposits'));
    }

    /**
     * Show full details of a single deposit before any admin action is taken.
     * URL: GET /admin/payments/deposited/{id}
     */
    public function depositDetail($id)
    {
        $deposit = Deposits::with('user')->findOrFail($id);

        // Keep the same filter as the list view (exclude 'used')
        // so we don't expose something that shouldn't be reviewed.
        if ($deposit->status === 'used') {
            return redirect()
                ->route('admin.payments')
                ->with('error', 'This deposit has already been consumed and cannot be reviewed.');
        }

        return view('admin.deposit-detail', compact('deposit'));
    }
    public function ApproveDeposit(Request $request)
    {
        $deposit = Deposits::where('id', $request->deposit)->first();
        if (!$deposit) {
            return redirect()->route('admin.payments')->with('error', 'Deposit not found.');
        }

        $user = User::where('id', $deposit->user_id)->first();
        if (!$user) {
            return redirect()->route('admin.payments')->with('error', 'User not found.');
        }

        if ($deposit->status === 'approved') {
            return redirect()->route('admin.payments')->with('error', 'This deposit is already approved.');
        }

        $deposit->update(['status' => 'approved']);

        // Manual Deposit & Pay Later requests should unlock dashboard access
        // only after admin approval, not when the user submits the request.
        if (($deposit->payment_context ?? null) === 'MANUAL_DEPOSIT_ACCESS') {
            $user->has_free_package = 'yes';
            if (!$user->has_paid_package || $user->has_paid_package === 'no') {
                $user->has_paid_package = 'standard';
            }
            // Admin approval unlocks the account/package gate, but it must not
            // bypass the contract. The contract middleware will send the user
            // to sign before dashboard access.
            $user->save();
        }

        // ── Credit DEPOSIT account with the deposited amount ──
        // Deposits are not withdrawable; withdrawals only use CASHOUT.
        $existing = $user->ChartAccount()->where('acc_type', 'DEPOSIT')->sum('amount');
        ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
            ['amount'  => $existing + $deposit->amount_deposited]
        );

        // ── Log transaction ──
        $trxNo = Transaction::generateTransactionNo();
        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'DEPOSIT',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'         => $deposit->amount_deposited,
                'method'         => $deposit->deposit_method,
                'currency'       => $deposit->currency_type,
                'approved_by'    => 'admin',
                'date'           => now()->toDateTimeString(),
                'status'         => 'approved',
                'username'       => $user->name,
            ]),
        ]);
        //         'amount' => $deposit->amount,
        //         'type'=>"DEPOSIT",
        //         'vup_income'=>"0.00",
        //         'date'=>Carbon::now(),
        //         'cash_25'=>
        //          'status'=>'success',



        //     ])
        // ]);


        // Send email notification
        Mail::to($user->email)->send(new SendDepositApproval($deposit));

    //     return redirect()->route('admin.deposits')->with('status', 'Deposit approved and payment recorded!');
    // }
        return redirect()->route("admin.payments")->with("message","Successfully Approved a Deposit!!");
    }

    public function OtherwiseDepositDecisions(Request $request){
        $deposit = Deposits::where('id',$request->deposit)->first();
        $user =User::where('id',$deposit->user_id)->first();
        $comment = $request->comment;
        $action = $request->action;
        // dd($user);


        $deposit->update(['status' => $action,'comment'=>$comment]);
        // Create a corresponding payment record
        // Payment::create([
        //     'user_id' => $deposit->user_id,
        //     'amount' => $deposit->amount,
        //     'status' => 'approved',
        //     'package_details' => [
        //         'package_name' => 'Sample Package',
        //         'validity_days' => 30,
        //     ],
        // ]);
        //  dd()
        if($action == 'rejected'){
            Mail::to($user->email)->send(new RejectDeposit($deposit, $comment));
        // dd("mail sent to $user->email");

            return redirect()->route("admin.payments")->with("message","Successfully Approved a Deposit!!");
        }

        if($action == 'cancelled'){
            Mail::to($user->email)->send(new CancelDeposit($deposit, $comment));
            return redirect()->route("admin.payments")->with("message","Successfully Approved a Deposit!!");
        }

        return redirect()->route("admin.payments")->with("message","Successfully Underreview!!");
    }


    public function index()
    {
        
        return view('admin.admin-dashboard');
        // return view('well');
    }

    public function plans()
    {
        $users = User::with([
                'have_activation_code',
                'investments' => function ($query) {
                    $query->where('status', '1')->latest();
                },
                'ChartAccount',
                'ownedTeams',
                'teamSide.owner',
                'approvedRanks',
            ])
            ->where('utype', '!=', 'ADM')
            ->where(function ($query) {
                $query->whereHas('investments', function ($paymentQuery) {
                        $paymentQuery->where('status', '1');
                    })
                    ->orWhereHas('have_activation_code', function ($activationQuery) {
                        $activationQuery->where('stutus', 'used')
                            ->whereNotNull('price')
                            ->where('price', '>', 0);
                    });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $this->attachMembershipSummaries($users->getCollection());

        return view('admin.admin-users-memberships', ['users' => $users]);
        // return view('well');
    }

    /**
     * Build display-ready membership values for the /admin/memberships-plan table.
     * The page is user-based, so the row shows the user's latest paid package,
     * with activation-code data used only when there is no paid payment row.
     */
    private function attachMembershipSummaries($users)
    {
        $payments = $users->flatMap(function ($user) {
            return $user->investments;
        });

        $adventureIds = $payments->filter(function ($payment) {
            return $this->isVenturePayment($payment);
        })->pluck('payable_id')->filter()->unique()->values();

        $fcPackageIds = $payments->filter(function ($payment) {
            return $this->isFcPayment($payment);
        })->pluck('payable_id')->filter()->unique()->values();

        $activationIds = $payments->filter(function ($payment) {
            return $this->isActivationPayment($payment);
        })->pluck('payable_id')->filter()->unique()->values();

        $adventures = $adventureIds->isNotEmpty()
            ? \App\Models\Adventures::whereIn('id', $adventureIds)->get()->keyBy('id')
            : collect();

        // Some legacy payment rows point to old adventure IDs that no longer exist.
        // Keep all packages available so the page can still resolve percentage,
        // duration and reward by plan/range fallback.
        $allAdventures = \App\Models\Adventures::all();

        $fcPackages = $fcPackageIds->isNotEmpty()
            ? \App\Models\FCpackage::whereIn('id', $fcPackageIds)->get()->keyBy('id')
            : collect();

        $paymentActivations = $activationIds->isNotEmpty()
            ? Activations::whereIn('id', $activationIds)->get()->keyBy('id')
            : collect();

        $userKeys = $users->flatMap(function ($user) {
            return [$user->user, $user->name];
        })->filter()->unique()->values();

        $emails = $users->pluck('email')->filter()->unique()->values();
        $phones = $users->pluck('phone')->filter()->unique()->values();

        $positions = $userKeys->isNotEmpty()
            ? Position::whereIn('user', $userKeys)->get()->keyBy(function ($position) {
                return strtolower((string) $position->user);
            })
            : collect();

        $teamLeaderLookup = collect();
        if ($userKeys->isNotEmpty() || $emails->isNotEmpty() || $phones->isNotEmpty()) {
            $teamLeaders = \App\Models\TeamLeader::where(function ($query) use ($userKeys, $emails, $phones) {
                if ($userKeys->isNotEmpty()) {
                    $query->whereIn('User_name', $userKeys);
                }
                if ($emails->isNotEmpty()) {
                    $query->orWhereIn('Email', $emails);
                }
                if ($phones->isNotEmpty()) {
                    $query->orWhereIn('Phone', $phones);
                }
            })->get();

            foreach ($teamLeaders as $teamLeader) {
                foreach ([$teamLeader->User_name, $teamLeader->Email, $teamLeader->Phone] as $key) {
                    if ($key) {
                        $teamLeaderLookup->put(strtolower((string) $key), $teamLeader);
                    }
                }
            }
        }

        foreach ($users as $user) {
            $summary = $this->membershipSummaryForUser(
                $user,
                $adventures,
                $allAdventures,
                $fcPackages,
                $paymentActivations,
                $positions,
                $teamLeaderLookup
            );

            $user->setRelation('membershipSummary', new \Illuminate\Support\Fluent($summary));
        }
    }

    private function membershipSummaryForUser(User $user, $adventures, $allAdventures, $fcPackages, $paymentActivations, $positions, $teamLeaderLookup)
    {
        $payment = $user->investments->first();
        $activation = $this->isUsedPaidActivation($user->have_activation_code) ? $user->have_activation_code : null;

        $source = $payment ? 'payment' : 'activation';
        $category = $payment ? strtoupper((string) $payment->category) : ($activation ? strtoupper((string) $activation->package) : '');
        $packageModel = null;
        $activationModel = null;

        if ($payment) {
            if ($this->isVenturePayment($payment)) {
                $packageModel = $adventures->get($payment->payable_id)
                    ?: $this->resolveAdventurePackage($payment, $allAdventures);
            } elseif ($this->isFcPayment($payment)) {
                $packageModel = $fcPackages->get($payment->payable_id);
            } elseif ($this->isActivationPayment($payment)) {
                $activationModel = $paymentActivations->get($payment->payable_id) ?: $activation;
            }
        } else {
            $activationModel = $activation;
        }

        $amount = $payment ? $this->numericValue($payment->paid) : 0;
        if ($payment && $amount <= 0) {
            $amount = $this->numericValue($payment->amount);
        }
        if (!$payment && $activationModel) {
            $amount = $this->numericValue($activationModel->price);
        }

        $planType = $this->planTypeLabel($payment, $packageModel, $activationModel, $user);
        $percentage = $this->percentageForMembership($payment, $packageModel, $activationModel);
        $durationDays = $this->durationDaysForMembership($payment, $packageModel, $activationModel);
        $startDate = $payment ? $payment->created_at : ($activationModel ? ($activationModel->updated_at ?: $activationModel->created_at) : null);
        $expirationDate = $payment ? $payment->expiration_date : $this->activationExpirationDate($activationModel, $durationDays);
        $approvedDate = $payment ? ($payment->updated_at ?: $payment->created_at) : ($activationModel ? ($activationModel->updated_at ?: $activationModel->created_at) : null);

        $dailyBonus = null;
        if ($percentage !== null && $amount > 0) {
            $dailyBonus = $this->isVenturePayment($payment)
                ? ($amount * 80 / 100) * ((float) $percentage / 100)
                : $amount * ((float) $percentage / 100);
        }

        $planStatus = $this->planStatusLabel($payment, $activationModel, $expirationDate);
        $rank = $user->approvedRanks->first();
        $position = $this->lookupByUserKeys($positions, $user);
        $teamLeader = $this->lookupByUserKeys($teamLeaderLookup, $user);
        $cashoutBalance = $user->ChartAccount->where('acc_type', 'CASHOUT')->sum('amount');
        $tradingBalance = $user->ChartAccount->where('acc_type', 'TRADING')->sum('amount');

        return [
            'plan_type' => $planType,
            'category' => $category ?: 'N/A',
            'amount' => $this->money($amount),
            'interest' => $percentage !== null ? rtrim(rtrim(number_format((float) $percentage, 2), '0'), '.') . '%' : 'N/A',
            'daily_bonus' => $dailyBonus !== null ? $this->money($dailyBonus) . ' / day' : 'N/A',
            'duration' => $durationDays ? $durationDays . ' days' : 'N/A',
            'reward' => $this->rewardLabel($payment, $packageModel, $activationModel, $amount),
            'plan_status' => $planStatus,
            'status_badge_class' => $this->statusBadgeClass($planStatus),
            'user_level' => $rank ? $rank->rank_name : 'Member',
            'membership_details' => $this->membershipDetailLines($source, $payment, $activationModel, $startDate, $expirationDate, $cashoutBalance, $tradingBalance),
            'role_position' => $this->rolePositionLines($position, $teamLeader),
            'groups' => $this->groupLines($user),
            'country' => $user->country ?: 'N/A',
            'approved_date' => $this->formatDate($approvedDate),
            'email_verified' => (bool) $user->email_verified_at,
            'contract_signed' => strtolower((string) $user->contract) === 'signed',
            'reference_lines' => $this->referenceLines($user, $payment, $activationModel),
        ];
    }

    private function isVenturePayment($payment)
    {
        if (!$payment) {
            return false;
        }
        return strtoupper((string) $payment->category) === 'VENTURE'
            || stripos((string) $payment->payable_type, 'Adventure') !== false;
    }

    private function isFcPayment($payment)
    {
        if (!$payment) {
            return false;
        }
        return strtoupper((string) $payment->category) === 'FC'
            || stripos((string) $payment->payable_type, 'FCpackage') !== false;
    }

    private function isActivationPayment($payment)
    {
        if (!$payment) {
            return false;
        }
        $category = strtoupper((string) $payment->category);
        return in_array($category, ['FT', 'TM'], true)
            || stripos((string) $payment->payable_type, 'Activations') !== false;
    }

    private function isUsedPaidActivation($activation)
    {
        return $activation
            && strtolower((string) $activation->stutus) === 'used'
            && $this->numericValue($activation->price) > 0;
    }

    private function resolveAdventurePackage($payment, $allAdventures)
    {
        if (!$payment || !$allAdventures || $allAdventures->isEmpty()) {
            return null;
        }

        $amount = $this->numericValue($payment->paid);
        if ($amount <= 0) {
            $amount = $this->numericValue($payment->amount);
        }
        $paymentPackage = strtolower(trim((string) $payment->package));

        // Best fallback: same plan and amount inside the configured package range.
        $match = $allAdventures->first(function ($adventure) use ($amount, $paymentPackage) {
            $plan = strtolower(trim((string) $adventure->plan));
            return $amount >= $this->numericValue($adventure->min_amount)
                && $amount <= $this->numericValue($adventure->max_amount)
                && $paymentPackage !== ''
                && $plan === $paymentPackage;
        });
        if ($match) {
            return $match;
        }

        // Next fallback: amount range only.
        $match = $allAdventures->first(function ($adventure) use ($amount) {
            return $amount >= $this->numericValue($adventure->min_amount)
                && $amount <= $this->numericValue($adventure->max_amount);
        });
        if ($match) {
            return $match;
        }

        // Final fallback: package text matches plan or name.
        return $allAdventures->first(function ($adventure) use ($paymentPackage) {
            return $paymentPackage !== '' && (
                strtolower(trim((string) $adventure->plan)) === $paymentPackage
                || strtolower(trim((string) $adventure->name)) === $paymentPackage
            );
        });
    }

    private function planTypeLabel($payment, $packageModel, $activationModel, User $user)
    {
        if ($this->isVenturePayment($payment) && $packageModel) {
            return trim($packageModel->name . ' - ' . $packageModel->plan);
        }

        if ($this->isFcPayment($payment) && $packageModel) {
            return $packageModel->name;
        }

        if ($activationModel) {
            return $activationModel->package ?: 'Activation Package';
        }

        if ($payment) {
            return $payment->package ?: $payment->category;
        }

        return $user->has_paid_package ?: 'N/A';
    }

    private function percentageForMembership($payment, $packageModel, $activationModel)
    {
        if ($this->isVenturePayment($payment) && $packageModel && $packageModel->percentage !== null) {
            return (float) $packageModel->percentage;
        }

        if ($activationModel && $activationModel->percentage !== null) {
            return (float) $activationModel->percentage;
        }

        return null;
    }

    private function durationDaysForMembership($payment, $packageModel, $activationModel)
    {
        if ($payment && (int) $payment->duration > 0) {
            return (int) $payment->duration;
        }

        if ($this->isVenturePayment($payment) && $packageModel && (int) $packageModel->duration > 0) {
            return (int) $packageModel->duration;
        }

        if ($activationModel) {
            foreach ([$activationModel->period, $activationModel->countdown, $activationModel->task_period] as $period) {
                if (preg_match('/\d+/', (string) $period, $matches)) {
                    return (int) $matches[0];
                }
            }
        }

        if ($payment && $payment->created_at && $payment->expiration_date) {
            try {
                return \Carbon\Carbon::parse($payment->created_at)->diffInDays(\Carbon\Carbon::parse($payment->expiration_date));
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    private function activationExpirationDate($activationModel, $durationDays)
    {
        if (!$activationModel || !$durationDays) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($activationModel->updated_at ?: $activationModel->created_at)
                ->addDays((int) $durationDays)
                ->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function planStatusLabel($payment, $activationModel, $expirationDate)
    {
        if ($payment) {
            $status = (string) $payment->status;
            if ($status !== '1') {
                $labels = [
                    '0' => 'Pending',
                    '2' => 'Underpaid',
                    '3' => 'Overpaid',
                ];
                return $labels[$status] ?? 'Pending';
            }

            if ((bool) $payment->is_expired || $this->dateIsPast($expirationDate)) {
                return 'Expired';
            }

            return 'Active';
        }

        if ($activationModel) {
            return strtolower((string) $activationModel->stutus) === 'used' ? 'Active' : ucfirst((string) $activationModel->stutus);
        }

        return 'N/A';
    }

    private function rewardLabel($payment, $packageModel, $activationModel, $amount)
    {
        if ($this->isVenturePayment($payment) && $packageModel && $packageModel->total_return !== null) {
            $returnPercentage = (float) $packageModel->total_return;
            $label = rtrim(rtrim(number_format($returnPercentage, 2), '0'), '.') . '% total return';
            if ($returnPercentage > 0 && $amount > 0) {
                $label .= ' (' . $this->money($amount * $returnPercentage / 100) . ')';
            }
            return $label;
        }

        if ($this->isFcPayment($payment) && $packageModel && $packageModel->default_token) {
            return number_format((float) $packageModel->default_token) . ' tokens';
        }

        if ($activationModel) {
            $parts = [];
            if ($activationModel->token) {
                $parts[] = number_format((float) $activationModel->token) . ' tokens';
            }
            if ($this->numericValue($activationModel->withdrawmax) > 0) {
                $parts[] = 'Withdraw max ' . $this->money($activationModel->withdrawmax);
            }
            return $parts ? implode(' / ', $parts) : 'N/A';
        }

        return 'N/A';
    }

    private function membershipDetailLines($source, $payment, $activationModel, $startDate, $expirationDate, $cashoutBalance, $tradingBalance)
    {
        $lines = [];
        if ($source === 'payment' && $payment) {
            $lines[] = 'Source: Payment #' . $payment->id . ' (' . strtoupper((string) $payment->category) . ')';
        } elseif ($activationModel) {
            $lines[] = 'Source: Activation code ' . $activationModel->code;
        }

        $lines[] = 'Start: ' . $this->formatDate($startDate);
        $lines[] = 'Expires: ' . ($expirationDate ? $this->formatDate($expirationDate) : 'N/A');
        $lines[] = 'Remaining: ' . $this->remainingTimeLabel($expirationDate);
        $lines[] = 'Cashout: ' . $this->money($cashoutBalance);
        $lines[] = 'Trading: ' . $this->money($tradingBalance);

        return $lines;
    }

    private function rolePositionLines($position, $teamLeader)
    {
        if ($position) {
            return [
                $position->position ?: 'Role assigned',
                'Status: ' . ($position->status ?: 'N/A'),
                'Award: ' . $this->money($position->award),
                'Daily bonus: ' . $this->money($position->daily_bonus),
            ];
        }

        if ($teamLeader) {
            return [
                'Team Leader',
                'Status: ' . ($teamLeader->status ?: 'N/A'),
            ];
        }

        return ['Member'];
    }

    private function groupLines(User $user)
    {
        $ownedTeams = $user->ownedTeams ?: collect();
        $leftCount = $ownedTeams->where('side', 'LEFT')->count();
        $rightCount = $ownedTeams->where('side', 'RIGHT')->count();
        $lines = [
            'Members: ' . $ownedTeams->count() . ' (L: ' . $leftCount . ', R: ' . $rightCount . ')',
        ];

        if ($user->teamSide) {
            $owner = $user->teamSide->owner;
            $ownerName = $owner ? ($owner->user ?: $owner->name) : 'Unknown sponsor';
            $lines[] = 'Parent: ' . $ownerName . ' / ' . $user->teamSide->side;
        } else {
            $lines[] = 'Parent: None';
        }

        return $lines;
    }

    private function referenceLines(User $user, $payment, $activationModel)
    {
        $lines = ['User #' . $user->id];
        if ($payment) {
            $lines[] = 'Payment #' . $payment->id;
        }
        if ($activationModel) {
            $lines[] = 'Code: ' . $activationModel->code;
        }
        return $lines;
    }

    private function lookupByUserKeys($lookup, User $user)
    {
        foreach ([$user->user, $user->name, $user->email, $user->phone] as $key) {
            if ($key && $lookup->has(strtolower((string) $key))) {
                return $lookup->get(strtolower((string) $key));
            }
        }
        return null;
    }

    private function statusBadgeClass($status)
    {
        $status = strtolower((string) $status);
        if ($status === 'active') {
            return 'bg-green-500';
        }
        if ($status === 'expired') {
            return 'bg-red-500';
        }
        if ($status === 'underpaid' || $status === 'pending') {
            return 'bg-yellow-500';
        }
        return 'bg-blue-500';
    }

    private function remainingTimeLabel($expirationDate)
    {
        if (!$expirationDate) {
            return 'N/A';
        }

        try {
            $today = \Carbon\Carbon::today();
            $expires = \Carbon\Carbon::parse($expirationDate)->startOfDay();
            if ($expires->lt($today)) {
                return 'Expired ' . $expires->diffInDays($today) . ' days ago';
            }
            return $today->diffInDays($expires) . ' days left';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function dateIsPast($date)
    {
        if (!$date) {
            return false;
        }

        try {
            return \Carbon\Carbon::parse($date)->endOfDay()->lt(\Carbon\Carbon::now());
        } catch (\Exception $e) {
            return false;
        }
    }

    private function formatDate($date)
    {
        if (!$date) {
            return 'N/A';
        }

        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d H:i');
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function money($amount)
    {
        return '$' . number_format($this->numericValue($amount), 2);
    }

    private function numericValue($value)
    {
        if ($value === null || $value === '') {
            return 0.0;
        }
        return (float) $value;
    }

      public function send(){
        $email='Maniraguhajp5@gmail.com';
        $username='Ubwoba1234';
        $mail=new reminder($email,$username);
        Mail::to($email)->send($mail);
       // return('send');
       return redirect()->back()->with('message','email sent weel to'.$email);
    }
      public function position()
    {
        return view('admin.positions');
        // return view('well');
    }
      public function pos_edit()
    {
        return view('admin.pos_edit');

    }
       public function position_approved()
    {
        $user=request()->query('user');
        try {
        $position=Position::where('user',$user)->first();
        $pos=$position->position;
        $position->update(['status'=>'approved','updated_at'=>now()]);
        return view('admin.positions')->with('message','user '.$user.' approved well on '.$pos.' position');

        } catch (Exception $e) {
        return view('admin.positions')->with('message','failed to approved user '.$user.' on '.$pos.' position');

        }

    }
      public function edit(Request $request)
    {
        $username=$request->user;
        $unique=$request->unique_task;
        $general=$request->general_task;
        $daily=$request->daily_bonus;
        $duration=$request->duration;
        $cashout=$request->cashout;
        $withdraw=$request->withdraw;

try {
    $position=Position::where('user',$username)->first();
    // $status='Unique task has been given';
    // if ($position->unique_task==$unique) {
    //         return view('admin.pos_edit',compact('status','username'));

    // }
$position->update([
     'unique_task'=>$unique,
    'general_task'=>$general,
    'cashout'=>$cashout,
    'unique_task'=>$unique,
    'withdraw'=>$withdraw,
    'daily_bonus'=>$daily,
    'duration'=>$duration,
]);
$status=$username.'s position edited weel';
            return view('admin.pos_edit',compact('status','username'));

        }
        catch (Exception $e) {
$status='failed to edit user position ';

            return view('admin.pos_edit',compact('status','username'));
        }
    }
        public function login()
    {
        return view('admin.login');
        // return view('well');
    }
         public function contacted()
    {
        return view('admin.contacted');
        // return view('well');
    }
  


public function check(Request $request) {

    $request->validate([
        'email'    => 'required',
        'password' => 'required',
    ]);
    $credentials = $request->only('email', 'password');
      if (Auth::attempt($credentials)) {
        $user = User::where('email', $request->email)->first();
        if ($user->utype == 'ADM') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->with('error', 'Oppes! You have no permission ');
        }
    }
    return redirect()->back()->with('error', 'Oppes! You have entered invalid credentials');
} 


    public function ft(){
        $activations = Activations::all();
        $tms = $activations->filter(function ($tm){
            return $tm->package == 'TM';
        });

        $activation =  $activations->filter(function ($tm){
            return $tm->package == 'FT';
        });


    // dd($activation);
        return view('admin.ft-manage',["tms"=>$tms,"FT"=>$activation]);
    }
        public function verify(){
            return view('admin.verify');
        }
        public function pending(){
          return view('admin.pending_users');
      }
      public function fc1(){
        return view('admin.fc1');
    }
      public function fc2(){
        return view('admin.fc2');
    }
      public function logout(){


        session()->forget('admin');
        return view('admin.login');
    }


    /**
     * Admin: all users and their referral bonus balances.
     */
    public function referralBonuses()
    {
        $users = User::where('utype', '!=', 'ADM')
            ->with(['ChartAccount', 'referrals'])
            ->get()
            ->map(function ($u) {
                $u->commission_balance = $u->ChartAccount->where('acc_type', 'COMMISSION')->sum('amount');
                $u->total_referrals    = $u->referrals->count();
                $u->active_referrals   = $u->referrals->filter(function ($r) {
                    return $r->investments()->where('status', 1)->where('category', 'VENTURE')->exists();
                })->count();
                return $u;
            })
            ->filter(fn($u) => $u->commission_balance > 0 || $u->total_referrals > 0)
            ->sortByDesc('commission_balance')
            ->values();

        $totalCommissionPaid = $users->sum('commission_balance');

        return view('admin.referral-bonuses', compact('users', 'totalCommissionPaid'));
    }

    /**
     * Admin: full referral bonus detail for one user.
     */
    public function referralBonusDetail($userId)
    {
        $user = User::with(['referrals', 'ChartAccount'])->findOrFail($userId);

        $commissionBalance = $user->ChartAccount->where('acc_type', 'COMMISSION')->sum('amount');

        $transactions = \App\Models\Transaction::where('user_id', $userId)
            ->where('transaction_type', 'COMMISSION')
            ->latest()
            ->get()
            ->map(function ($t) {
                $d = json_decode($t->transaction_details, true) ?? [];
                $t->parsed_amount      = $d['amount'] ?? 0;
                $t->parsed_description = $d['description'] ?? 'Referral Bonus';
                $t->from_user          = $d['username'] ?? '—';
                return $t;
            });

        $directReferrals = $user->referrals()
            ->with(['investments' => fn($q) => $q->where('status', 1)->where('category', 'VENTURE')])
            ->get()
            ->map(function ($r) {
                $invested = $r->investments->sum('amount');
                $r->total_invested = $invested;
                $r->bonus_earned   = round($invested * 10 / 100, 2);
                $r->is_active      = $invested > 0;
                return $r;
            });

        $indirectReferrals = collect();
        foreach ($user->referrals as $direct) {
            foreach ($direct->referrals as $indirect) {
                $invested = $indirect->investments()->where('status', 1)->where('category', 'VENTURE')->sum('amount');
                $indirect->total_invested   = $invested;
                $indirect->bonus_earned     = round($invested * 1 / 100, 2);
                $indirect->referred_through = $direct->name;
                $indirect->is_active        = $invested > 0;
                $indirectReferrals->push($indirect);
            }
        }

        $directBonusTotal   = round($directReferrals->sum('bonus_earned'), 2);
        $indirectBonusTotal = round($indirectReferrals->sum('bonus_earned'), 2);

        return view('admin.referral-bonus-detail', compact(
            'user', 'commissionBalance', 'transactions',
            'directReferrals', 'indirectReferrals',
            'directBonusTotal', 'indirectBonusTotal'
        ));
    }

    // ─── Token Withdrawals (admin approve/reject) ────────────────────────
    public function tokenWithdrawals()
    {
        $pending   = \App\Models\TokenWithdrawal::where('status', 'pending')->with('user')->latest()->get();
        $completed = \App\Models\TokenWithdrawal::whereIn('status', ['approved','rejected'])->with('user')->latest()->paginate(30);
        // Gas fee totals per user (admin-only view)
        $gasFees   = \App\Models\ChartAccount::where('acc_type', 'GAS_FEE')
                        ->with('user')
                        ->get()
                        ->groupBy('user_id')
                        ->map(fn($rows) => $rows->sum('amount'));
        return view('admin.token-withdrawals', compact('pending', 'completed', 'gasFees'));
    }

    public function approveTokenWithdrawal(Request $request)
    {
        $tw = \App\Models\TokenWithdrawal::findOrFail($request->token_withdrawal_id);
        if ($tw->status !== 'pending') {
            return redirect()->route('admin.token-withdrawals')->with('error', 'Already processed.');
        }
        $tw->update(['status' => 'approved', 'admin_note' => $request->admin_note, 'approved_by' => Auth::id()]);
        return redirect()->route('admin.token-withdrawals')->with('message', 'Token withdrawal approved.');
    }

    public function rejectTokenWithdrawal(Request $request)
    {
        $tw = \App\Models\TokenWithdrawal::findOrFail($request->token_withdrawal_id);
        if ($tw->status !== 'pending') {
            return redirect()->route('admin.token-withdrawals')->with('error', 'Already processed.');
        }
        // Refund tokens back to user's FREE_TOKEN
        $user = User::find($tw->user_id);
        if ($user) {
            $bal = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'FREE_TOKEN'],
                ['amount'  => $bal + $tw->token_amount]
            );
        }
        $tw->update(['status' => 'rejected', 'admin_note' => $request->admin_note ?? 'Rejected by admin']);
        return redirect()->route('admin.token-withdrawals')->with('message', 'Token withdrawal rejected and tokens refunded.');
    }

    public function freeUserRegisterPage()
    {
        $settings = \App\Models\WithdrawalSetting::current();
        return view('admin.settings.free-user-on-register', compact('settings'));
    }

    public function updateFreeUserRegister(Request $request)
    {
        $settings = \App\Models\WithdrawalSetting::current();
        $settings->update([
            'allow_free_dashboard_access' => (bool) $request->input('allow_free_dashboard_access'),
        ]);
        return back()->with('success', 'Registration dashboard access settings updated.');
    }

    public function withdrawalHistory()
    {
        $history = \App\Models\withdrawals::whereIn('status', ['completed', 'failed'])->latest()->paginate(25);
        return view('admin.withdrawal-history', compact('history'));
    }

    public function teamLeadersList()
    {
        $pending = \App\Models\TeamLeader::where('status', 'pending')->latest()->get();
        $confirmed = \App\Models\TeamLeader::where('status', 'confirmed')->latest()->get();
        $rejected = \App\Models\TeamLeader::where('status', 'rejected')->latest()->get();
        $suspended = \App\Models\TeamLeader::where('status', 'suspended')->latest()->get();

        // Auditing collections
        $pendingEvents = \App\Models\TeamLeaderEvent::where('status', 'pending')->latest()->get();
        $pendingProofs = \App\Models\TeamLeaderEvent::where('proof_submitted', true)->where('proof_status', 'pending')->latest()->get();
        $pendingSocials = \App\Models\TeamLeaderSocial::where('status', 'pending')->latest()->get();
        $approvedSocials = \App\Models\TeamLeaderSocial::where('status', 'approved')->latest()->get();

        return view('admin.team-leaders', compact('pending', 'confirmed', 'rejected', 'suspended', 'pendingEvents', 'pendingProofs', 'pendingSocials', 'approvedSocials'));
    }

    /**
     * Show full detail page for a single team leader application.
     * Admin can view / click / copy WhatsApp & Telegram links here,
     * then approve or reject.
     */
    public function showTeamLeader($id)
    {
        $leader = \App\Models\TeamLeader::findOrFail($id);

        // ── Performance / monitoring data ──
        $user = \App\Models\User::where('user', $leader->User_name)->first();
        $activation = \App\Models\Activations::where('email', $leader->Email)
            ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
            ->first();

        $performance = [
            'duration_days'     => $activation ? (int)($activation->period ?? 60) : 60,
            'activation_date'   => $activation ? $activation->updated_at : null,
            'expiry_date'       => null,
            'days_elapsed'      => 0,
            'days_remaining'    => 0,
            'is_expired'        => false,
            'direct_referrals'  => 0,
            'indirect_referrals'=> 0,
            'total_referrals'   => 0,
            'active_referrals'  => 0,
            'active_indirect_referrals' => 0,
            'total_active_referrals'    => 0,
            'referral_bonuses'  => 0,
            'tasks'             => $activation ? $activation->task : '',
            // Structured tasks with persisted tick state for Performance Monitoring.
            'task_items'        => \App\Models\TeamLeaderTaskCompletion::buildTaskList(
                                        $user->id ?? 0,
                                        $activation ? $activation->task : ''
                                   ),
            'price'             => $activation ? (float)($activation->price ?? 0) : 0,
            'tokens'            => $activation ? (float)($activation->token ?? 0) : 0,
        ];

        if ($performance['activation_date']) {
            $start = \Carbon\Carbon::parse($performance['activation_date']);
            $end   = $start->copy()->addDays($performance['duration_days']);
            $now   = \Carbon\Carbon::now();
            $performance['expiry_date']    = $end;
            $performance['days_elapsed']   = $start->diffInDays($now);
            $performance['days_remaining'] = max(0, $now->diffInDays($end, false));
            $performance['is_expired']     = $now->greaterThan($end);
        }

        if ($user) {
            $performance['direct_referrals'] = $user->referrals()->count();

            // ── Indirect referrals: walk the ENTIRE referral tree (no depth limit) ──
            $indirectCount = 0;
            $activeIndirectCount = 0;
            $visited = [$user->id];
            $currentLevelIds = $user->referrals()->pluck('id')->all();

            while (!empty($currentLevelIds)) {
                $visited = array_merge($visited, $currentLevelIds);
                $indirectCount += count($currentLevelIds);

                // Count active (with paid package) at this level
                $activeIndirectCount += (int) \App\Models\User::whereIn('id', $currentLevelIds)
                    ->where('has_paid_package', '!=', 'no')
                    ->where('has_paid_package', '!=', '')
                    ->count();

                // Next level
                $currentLevelIds = \App\Models\User::whereIn('referee_id', $currentLevelIds)
                    ->whereNotIn('id', $visited)
                    ->pluck('id')->all();
            }

            $performance['indirect_referrals']       = $indirectCount;
            $performance['total_referrals']           = $performance['direct_referrals'] + $indirectCount;
            $performance['active_referrals']          = $performance['active_referrals_direct'] ?? 0;
            $performance['active_indirect_referrals'] = $activeIndirectCount;
            $performance['total_active_referrals']    = ($performance['active_referrals_direct'] ?? 0) + $activeIndirectCount;

            // Direct active referrals
            $performance['active_referrals'] = (int) \App\Models\User::whereIn('id', $user->referrals()->pluck('id'))
                ->where('has_paid_package', '!=', 'no')
                ->where('has_paid_package', '!=', '')
                ->count();

            $performance['referral_bonuses'] = (float) \App\Models\ReferralBonus::where('user_id', $user->id)
                ->where('status', '!=', 'reversed')
                ->sum('bonus_amount');
        }

        $credit = $leader->superLeaderCredit;

        return view('admin.team-leader-detail', compact('leader', 'performance', 'credit', 'user'));
    }

    public function approveEventPlan($id)
    {
        $event = \App\Models\TeamLeaderEvent::findOrFail($id);
        $event->update(['status' => 'approved']);
        return redirect()->back()->with('message', "Event plan '{$event->title}' has been APPROVED.");
    }

    public function rejectEventPlan($id)
    {
        $event = \App\Models\TeamLeaderEvent::findOrFail($id);
        $event->update(['status' => 'rejected']);
        return redirect()->back()->with('message', "Event plan '{$event->title}' has been REJECTED.");
    }

    public function approveEventProof($id)
    {
        $event = \App\Models\TeamLeaderEvent::findOrFail($id);
        $event->update(['proof_status' => 'approved']);
        return redirect()->back()->with('message', "Performance proof for '{$event->title}' has been APPROVED.");
    }

    public function rejectEventProof($id)
    {
        $event = \App\Models\TeamLeaderEvent::findOrFail($id);
        $event->update(['proof_status' => 'rejected']);
        return redirect()->back()->with('message', "Performance proof for '{$event->title}' has been REJECTED.");
    }

    public function approveSocialProfile($id)
    {
        $social = \App\Models\TeamLeaderSocial::findOrFail($id);
        $social->update(['status' => 'approved']);
        return redirect()->back()->with('message', "Social ambassador link has been APPROVED.");
    }

    public function rejectSocialProfile($id)
    {
        $social = \App\Models\TeamLeaderSocial::findOrFail($id);
        $social->update(['status' => 'rejected']);
        return redirect()->back()->with('message', "Social ambassador link has been REJECTED.");
    }

    public function approveTeamLeader(Request $request, $id)
    {
        $leader = \App\Models\TeamLeader::findOrFail($id);
        $level  = $leader->leadership_level ?? 'TEAM_LEADER';

        $rules = [
            'activation_code' => 'required|string|unique:activations,code',
            'duration'        => 'required|integer|min:1',
            'tasks'           => 'required|string|max:1000',
            'tokens'          => 'required|numeric|min:0',
            'price'           => 'required|numeric|min:0',
        ];

        if ($level === 'SUPER_LEADER') {
            $rules['credit_amount'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        $leader->update(['status' => 'confirmed']);

        // Create the unique Activation Code
        $activation = \App\Models\Activations::create([
            'code'         => strtoupper($request->activation_code),
            'package'      => $level === 'SUPER_LEADER' ? 'SUPER_LEADER' : 'TEAM_LEADER',
            'stutus'       => 'not',
            'is_auto_code' => false,
            'token'        => $request->tokens,
            'price'        => $request->price,
            'task'         => $request->tasks,
            'period'       => $request->duration,
            'percentage'   => 0.0,
            'withdrawmax'  => 999999.0,
            'email'        => $leader->Email,
        ]);

        // Update the associated User
        $user = \App\Models\User::where('user', $leader->User_name)->first();
        if ($user) {
            $user->update(['has_request' => 'approved']);
        }

        // Create SUPER LEADER credit record (always, even if amount is 0)
        if ($level === 'SUPER_LEADER') {
            $creditAmount = $request->credit_amount ?? 0;

            \App\Models\SuperLeaderCredit::create([
                'team_leader_id'        => $leader->id,
                'user_id'               => $user ? $user->id : null,
                'activation_id'         => $activation->id,
                'credit_amount'         => $creditAmount,
                'remaining_credit'      => $creditAmount,
                'cashout_amount'        => 0,
                'sales_turnover_target' => 10000,
                'turnover_target_percent' => 0,
                'turnover_reward_percent' => 0,
                'auto_withdrawal_percent' => 0,
                'status'                => 'pending',
            ]);

            // Also sync to the legacy `credits` table so the existing
            // user dashboard credit box ($activation->myCredit) works.
            \App\Models\Credit::create([
                'activation_id' => $activation->id,
                'amount'        => $creditAmount,
                'status'        => 'pending',
            ]);
        }

        return redirect()->route('admin.team-leaders.show', $leader->id)
            ->with('message', "{$level} {$leader->Names} has been approved. Code: " . strtoupper($request->activation_code));
    }

    /**
     * Create a SUPER LEADER credit record (fallback if not created during approval).
     */
    public function createCredit(Request $request, $leaderId)
    {
        $request->validate([
            'credit_amount'             => 'required|numeric|min:0',
            'sales_turnover_target'     => 'required|numeric|min:0',
            'turnover_target_percent'   => 'required|numeric|min:0|max:100',
            'turnover_reward_percent'   => 'required|numeric|min:0|max:100',
            'auto_withdrawal_percent'   => 'required|numeric|min:0|max:100',
        ]);

        $leader = \App\Models\TeamLeader::findOrFail($leaderId);
        $user = \App\Models\User::where('user', $leader->User_name)->first();
        $activation = \App\Models\Activations::where('email', $leader->Email)->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])->first();

        $superCredit = \App\Models\SuperLeaderCredit::create([
            'team_leader_id'          => $leader->id,
            'user_id'                 => $user ? $user->id : null,
            'activation_id'           => $activation ? $activation->id : null,
            'credit_amount'           => $request->credit_amount,
            'remaining_credit'        => $request->credit_amount,
            'cashout_amount'          => 0,
            'sales_turnover_target'   => $request->sales_turnover_target,
            'turnover_target_percent' => $request->turnover_target_percent,
            'turnover_reward_percent' => $request->turnover_reward_percent,
            'auto_withdrawal_percent' => $request->auto_withdrawal_percent,
            'status'                  => 'pending',
        ]);

        // Sync to legacy credits table for dashboard compatibility
        if ($activation) {
            \App\Models\Credit::create([
                'activation_id' => $activation->id,
                'amount'        => $request->credit_amount,
                'status'        => 'pending',
            ]);
        }

        return redirect()->back()->with('message', 'SUPER LEADER credit record created successfully.');
    }

    /**
     * Update SUPER LEADER credit details & toggle active/pending.
     */
    public function updateCredit(Request $request, $creditId)
    {
        $request->validate([
            'sales_turnover_target'     => 'required|numeric|min:0',
            'turnover_target_percent'   => 'required|numeric|min:0|max:100',
            'turnover_reward_percent'   => 'required|numeric|min:0|max:100',
            'auto_withdrawal_percent'   => 'required|numeric|min:0|max:100',
            'credit_status'             => 'required|string|in:active,pending',
        ]);

        $credit = \App\Models\SuperLeaderCredit::findOrFail($creditId);

        $credit->update([
            'sales_turnover_target'     => $request->sales_turnover_target,
            'turnover_target_percent'   => $request->turnover_target_percent,
            'turnover_reward_percent'   => $request->turnover_reward_percent,
            'auto_withdrawal_percent'   => $request->auto_withdrawal_percent,
            'status'                    => $request->credit_status,
            'activated_at'              => $request->credit_status === 'active' && !$credit->activated_at ? now() : $credit->activated_at,
        ]);

        // Sync status to legacy credits table for dashboard compatibility
        if ($credit->activation_id) {
            \App\Models\Credit::where('activation_id', $credit->activation_id)
                ->update(['status' => $request->credit_status]);
        }

        return redirect()->back()->with('message', 'SUPER LEADER credit details updated. Status: ' . strtoupper($request->credit_status));
    }

    // ═══════════════════════════════════════════════════════════
    //  OFFICIAL VIDEO PROMOTIONS & TUTORIALS
    // ═══════════════════════════════════════════════════════════
    public function leaderVideos()
    {
        return view('admin.leader-videos');
    }

    public function leaderVideoUpload()
    {
        return view('admin.leader-video-upload');
    }

    public function leaderVideoStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $videoUrl = null;
        $videoType = 'youtube';

        // Handle file upload
        if ($request->hasFile('video_file')) {
            $videoUrl = $request->file('video_file')->store('team_leader_videos', 'public');
            $videoType = 'mp4';
        }

        // Or use YouTube URL
        if ($request->filled('video_url')) {
            $videoUrl = $request->video_url;
            $videoType = 'youtube';
        }

        if (!$videoUrl) {
            return redirect()->back()->with('error', 'Please upload a video file or provide a YouTube URL.');
        }

        \App\Models\TeamLeaderVideo::create([
            'title'            => $request->title,
            'video_url'        => $videoUrl,
            'video_type'       => $videoType,
            'description'      => $request->description ?? '',
            'duration_seconds' => $request->duration_seconds ?? 60,
            'target_region'    => $request->target_region ?? '',
            'status'           => 'approved',
            'uploaded_by'      => 'admin',
        ]);

        return redirect()->route('admin.leader-videos')->with('message', 'Video uploaded and approved successfully!');
    }

    public function leaderVideoApprove($id)
    {
        \App\Models\TeamLeaderVideo::where('id', $id)->update(['status' => 'approved']);
        return redirect()->back()->with('message', 'Video approved.');
    }

    public function leaderVideoReject(Request $request, $id)
    {
        \App\Models\TeamLeaderVideo::where('id', $id)->update([
            'status' => 'rejected',
            'rejected_reason' => $request->reason ?? '',
        ]);
        return redirect()->back()->with('message', 'Video rejected.');
    }

    public function leaderVideoDelete($id)
    {
        \App\Models\TeamLeaderVideo::where('id', $id)->delete();
        return redirect()->back()->with('message', 'Video deleted.');
    }

    // ═══════════════════════════════════════════════════════════
    //  MARKETING BANNERS & CREATIVES
    // ═══════════════════════════════════════════════════════════
    public function leaderBanners()
    {
        return view('admin.leader-banners');
    }

    public function leaderBannerUpload()
    {
        return view('admin.leader-banner-upload');
    }

    public function leaderBannerStore(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'banner_file' => 'required|image|max:10240',
        ]);

        $imagePath = $request->file('banner_file')->store('team_leader_banners', 'public');

        \App\Models\TeamLeaderBanner::create([
            'title'       => $request->title,
            'image_path'  => $imagePath,
            'description' => $request->description ?? '',
            'landing_url' => $request->landing_url ?? '',
            'target_region' => $request->target_region ?? '',
            'status'      => 'approved',
            'uploaded_by' => 'admin',
        ]);

        return redirect()->route('admin.leader-banners')->with('message', 'Banner uploaded and approved successfully!');
    }

    public function leaderBannerApprove($id)
    {
        \App\Models\TeamLeaderBanner::where('id', $id)->update(['status' => 'approved']);
        return redirect()->back()->with('message', 'Banner approved.');
    }

    public function leaderBannerReject(Request $request, $id)
    {
        \App\Models\TeamLeaderBanner::where('id', $id)->update([
            'status' => 'rejected',
            'rejected_reason' => $request->reason ?? '',
        ]);
        return redirect()->back()->with('message', 'Banner rejected.');
    }

    public function leaderBannerDelete($id)
    {
        \App\Models\TeamLeaderBanner::where('id', $id)->delete();
        return redirect()->back()->with('message', 'Banner deleted.');
    }

    // ═══════════════════════════════════════════════════════════
    //  TEAM LEADERS ANNOUNCEMENTS
    // ═══════════════════════════════════════════════════════════
    public function announcementsList()
    {
        $announcements = \App\Models\TeamLeaderAnnouncement::orderBy('sort_order')->latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function announcementCreate()
    {
        return view('admin.announcements.create');
    }

    public function announcementStore(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        \App\Models\TeamLeaderAnnouncement::create([
            'title'     => $request->title,
            'content'   => $request->content,
            'sort_order'=> $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.announcements.index')->with('message', 'Announcement created successfully!');
    }

    public function announcementToggle($id)
    {
        $ann = \App\Models\TeamLeaderAnnouncement::findOrFail($id);
        $ann->update(['is_active' => !$ann->is_active]);
        return redirect()->back()->with('message', 'Announcement status updated!');
    }

    public function announcementDelete($id)
    {
        $ann = \App\Models\TeamLeaderAnnouncement::findOrFail($id);
        $ann->delete();
        return redirect()->back()->with('message', 'Announcement deleted!');
    }

    public function rejectTeamLeader($id)
    {
        $leader = \App\Models\TeamLeader::findOrFail($id);
        $leader->update(['status' => 'rejected']);

        return redirect()->back()->with('message', "Team Leader {$leader->Names} application has been rejected.");
    }

    public function suspendTeamLeader($id)
    {
        $leader = \App\Models\TeamLeader::findOrFail($id);
        $leader->update(['status' => 'suspended']);

        return redirect()->back()->with('message', "Team Leader {$leader->Names} has been suspended.");
    }

    public function reactivateTeamLeader($id)
    {
        $leader = \App\Models\TeamLeader::findOrFail($id);
        $leader->update(['status' => 'confirmed']);

        return redirect()->back()->with('message', "Team Leader {$leader->Names} has been reactivated.");
    }

    public function teamLeaderTokenReleases()
    {
        // Fetch all Team Leader records with 'confirmed' status
        $leaders = \App\Models\TeamLeader::where('status', 'confirmed')->latest()->get();

        $releases = collect();

        foreach ($leaders as $leader) {
            $user = \App\Models\User::where('user', $leader->User_name)
                ->orWhere('email', $leader->Email)
                ->first();

            if (!$user) {
                continue;
            }

            // Find associated activation code
            $activation = \App\Models\Activations::where('email', $user->email)
                ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
                ->first();

            if (!$activation) {
                $activation = \App\Models\Activations::where('user_id', $user->id)
                    ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
                    ->first();
            }

            // Find or create TeamLeaderTokenRelease record
            $release = \App\Models\TeamLeaderTokenRelease::where('user_id', $user->id)->first();

            $totalLockedTokens = $activation ? (float)$activation->token : 0.0;
            if ($totalLockedTokens <= 0) {
                $bal = \App\Models\balance::where('user', $user->id)->first();
                if ($bal) {
                    $totalLockedTokens = (float)$bal->reserved_token;
                }
            }

            $activatedAt  = $activation ? \Carbon\Carbon::parse($activation->updated_at) : ($leader->created_at);
            $durationDays = $activation ? (int)($activation->period ?? 60) : 60;
            $eligibleAt   = $activatedAt ? $activatedAt->copy()->addDays($durationDays) : null;
            $isDurationOver = $eligibleAt ? now()->greaterThanOrEqualTo($eligibleAt) : false;

            if (!$release) {
                $release = \App\Models\TeamLeaderTokenRelease::create([
                    'user_id'                 => $user->id,
                    'team_leader_id'          => $leader->id,
                    'activation_id'           => $activation ? $activation->id : null,
                    'total_locked_tokens'     => $totalLockedTokens,
                    'released_tokens'         => 0,
                    'remaining_locked_tokens' => $totalLockedTokens,
                    'status'                  => 'pending',
                    'duration_days'           => $durationDays,
                    'activated_at'            => $activatedAt,
                    'eligible_at'             => $eligibleAt,
                ]);
            } else {
                if ($release->total_locked_tokens <= 0 && $totalLockedTokens > 0) {
                    $release->update([
                        'total_locked_tokens'     => $totalLockedTokens,
                        'remaining_locked_tokens' => max(0, $totalLockedTokens - $release->released_tokens),
                        'activation_id'           => $activation ? $activation->id : $release->activation_id,
                        'activated_at'            => $activatedAt,
                        'eligible_at'             => $eligibleAt,
                    ]);
                }
            }

            // Performance metrics snapshot for admin review
            $taskList = \App\Models\TeamLeaderTaskCompletion::buildTaskList($user->id, $activation ? $activation->task : '');

            $performance = [
                'tasks_completed' => $taskList['completed'],
                'tasks_total'     => $taskList['total'],
                'events_approved' => \App\Models\TeamLeaderEvent::where('user_id', $user->id)->where('status', 'approved')->count(),
                'events_total'    => \App\Models\TeamLeaderEvent::where('user_id', $user->id)->count(),
                'socials_approved'=> \App\Models\TeamLeaderSocial::where('user_id', $user->id)->where('status', 'approved')->count(),
                'referrals_count' => $user->referrals()->count(),
                'active_referrals'=> $user->referrals()->where('has_paid_package', '!=', 'no')->where('has_paid_package', '!=', '')->count(),
            ];

            $releases->push([
                'release'          => $release,
                'leader'           => $leader,
                'user'             => $user,
                'activation'       => $activation,
                'is_duration_over' => $isDurationOver,
                'performance'      => $performance,
            ]);
        }

        $pendingReleases   = $releases->where('release.status', 'pending')->values();
        $approvedReleases  = $releases->where('release.status', 'approved')->values();
        $rejectedReleases  = $releases->where('release.status', 'rejected')->values();

        return view('admin.team-leader-token-releases', compact('releases', 'pendingReleases', 'approvedReleases', 'rejectedReleases'));
    }

    public function approveTokenRelease(\Illuminate\Http\Request $request, $id)
    {
        $release = \App\Models\TeamLeaderTokenRelease::findOrFail($id);

        if ($release->status === 'approved') {
            return redirect()->back()->with('error', 'Tokens for this leader have already been released.');
        }

        $user = \App\Models\User::findOrFail($release->user_id);
        $amountToRelease = (float) ($request->input('release_amount') ?: $release->total_locked_tokens);

        if ($amountToRelease <= 0) {
            return redirect()->back()->with('error', 'Invalid release amount specified.');
        }

        // 1. Credit AVAILABLE_TOKEN in ChartAccount
        $currentAvailable = (float) $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
            ['amount' => $currentAvailable + $amountToRelease]
        );

        // 2. Record Transaction
        $trxNo = \App\Models\Transaction::generateTransactionNo();
        \App\Models\Transaction::create([
            'user_id'          => $user->id,
            'transaction_no'   => $trxNo,
            'transaction_type' => 'TOKEN_RELEASE',
            'receiver_id'      => 0,
            'transaction_details' => json_encode([
                'type'        => 'TEAM_LEADER_TOKEN_RELEASE',
                'amount'      => $amountToRelease,
                'description' => 'Team Leader locked tokens released to Available Token by admin.',
                'admin_id'    => \Illuminate\Support\Facades\Auth::id(),
                'approved_at' => now()->toDateTimeString(),
                'notes'       => $request->input('notes', ''),
            ]),
        ]);

        // 3. Update release record
        $release->update([
            'released_tokens'         => $release->released_tokens + $amountToRelease,
            'remaining_locked_tokens' => max(0, $release->total_locked_tokens - ($release->released_tokens + $amountToRelease)),
            'status'                  => 'approved',
            'approved_at'             => now(),
            'admin_id'                => \Illuminate\Support\Facades\Auth::id(),
            'admin_notes'             => $request->input('notes', 'Approved & tokens released to Available Token'),
        ]);

        return redirect()->back()->with('message', "Successfully approved and released " . number_format($amountToRelease, 0) . " tokens to Available Token for leader {$user->name}.");
    }

    public function rejectTokenRelease(\Illuminate\Http\Request $request, $id)
    {
        $release = \App\Models\TeamLeaderTokenRelease::findOrFail($id);

        $release->update([
            'status'      => 'rejected',
            'admin_id'    => \Illuminate\Support\Facades\Auth::id(),
            'admin_notes' => $request->input('notes', 'Release rejected by admin upon performance review.'),
        ]);

        return redirect()->back()->with('message', 'Team Leader token release request has been rejected.');
    }

    public function tmAutoActivationsIndex()
    {
        $activations = \App\Models\Activations::whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
            ->latest()
            ->get();

        return view('admin.tm-auto-activations', compact('activations'));
    }

    public function tmAutoActivationStore(\Illuminate\Http\Request $request)
    {
        $rules = [
            'activation_code' => 'required|string|max:50|unique:activations,code',
            'leadership_level'  => 'required|string|in:TEAM_LEADER,SUPER_LEADER',
            'duration'        => 'required|integer|min:1',
            'tokens'          => 'required|numeric|min:0',
            'price'           => 'required|numeric|min:0',
            'tasks'           => 'required|string|max:1000',
            'memo'            => 'nullable|string|max:255',
        ];

        if ($request->leadership_level === 'SUPER_LEADER') {
            $rules['credit_amount']           = 'required|numeric|min:0';
            $rules['sales_turnover_target']   = 'required|numeric|min:0';
            $rules['turnover_target_percent'] = 'required|numeric|min:0|max:100';
            $rules['turnover_reward_percent'] = 'required|numeric|min:0|max:100';
            $rules['auto_withdrawal_percent'] = 'required|numeric|min:0|max:100';
        }

        $request->validate($rules);

        $code = strtoupper(trim($request->activation_code));

        $creditConditions = null;
        if ($request->leadership_level === 'SUPER_LEADER') {
            $creditConditions = json_encode([
                'credit_amount'           => (float) $request->input('credit_amount', 1000),
                'sales_turnover_target'   => (float) $request->input('sales_turnover_target', 10000),
                'turnover_target_percent' => (float) $request->input('turnover_target_percent', 0),
                'turnover_reward_percent' => (float) $request->input('turnover_reward_percent', 0),
                'auto_withdrawal_percent' => (float) $request->input('auto_withdrawal_percent', 0),
            ]);
        }

        \App\Models\Activations::create([
            'code'              => $code,
            'package'           => $request->leadership_level,
            'stutus'            => 'not',
            'is_auto_code'      => true,
            'credit_conditions' => $creditConditions,
            'token'             => $request->tokens,
            'price'             => $request->price,
            'task'              => $request->tasks,
            'period'            => $request->duration,
            'percentage'        => 0.0,
            'withdrawmax'       => 999999.0,
            'email'             => $request->memo ?: '',
        ]);

        return redirect()->back()->with('message', "TM Auto Activation Code '{$code}' ({$request->leadership_level}) generated successfully with custom credit conditions! Any user redeeming this code will automatically activate with those exact credit conditions.");
    }

    public function tmAutoActivationDelete($id)
    {
        $activation = \App\Models\Activations::findOrFail($id);

        if ($activation->stutus === 'used') {
            return redirect()->back()->with('error', 'Cannot delete an activation code that has already been used.');
        }

        $activation->delete();

        return redirect()->back()->with('message', 'TM Auto Activation Code deleted successfully.');
    }

}
