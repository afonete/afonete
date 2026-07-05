<?php

namespace App\Services;

use App\Models\adventures;
use App\Models\BlockchainAuditLog;
use App\Models\ChartAccount;
use App\Models\Deposits;
use App\Models\Earnings;
use App\Models\FCpackage;
use App\Models\Payment as Paymodel;
use App\Models\TokenSetting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Creates and fulfils direct USDT TRC20 package-payment invoices.
 *
 * Users pay to their unique TRON address. The blockchain scanner marks the
 * matching deposit approved and calls activateFromDeposit(), which activates
 * the requested UVP/FC package without Plisio.
 */
class DirectPackagePaymentService
{
    public const PAYMENT_CONTEXT = 'PACKAGE_PAYMENT';
    public const DEPOSIT_METHOD  = 'AUTO_TRON_PACKAGE';
    public const USED_METHOD     = 'AUTO_TRON_PACKAGE_USED';
    public const PAYMENT_WINDOW_MINUTES = 15;

    public function createPendingIntent(User $user, string $packageType, int $packageId, ?float $amount = null): Deposits
    {
        $resolved = $this->resolvePackage($packageType, $packageId, $amount);

        $depositAddress = app(TronBlockchainService::class)->getOrCreateDepositAddressForUser($user);
        if (!$depositAddress) {
            throw new \RuntimeException('Automatic USDT TRC20 address is not available. Please contact support.');
        }

        return DB::transaction(function () use ($user, $resolved, $depositAddress) {
            $existing = Deposits::where('user_id', $user->id)
                ->where('payment_context', self::PAYMENT_CONTEXT)
                ->where('package_type', $resolved['type'])
                ->where('package_id', $resolved['id'])
                ->where('status', 'pending')
                ->where('expires_at', '>', now())
                ->whereBetween('amount_deposited', [$resolved['amount'] - 0.000001, $resolved['amount'] + 0.000001])
                ->latest()
                ->lockForUpdate()
                ->first();

            if ($existing) {
                if (!$existing->deposit_address) {
                    $existing->update([
                        'deposit_address'     => $depositAddress->address,
                        'user_wallet_address' => $existing->user_wallet_address ?: $depositAddress->address,
                        'network'             => 'TRC-20',
                    ]);
                }

                return $existing;
            }

            $deposit = Deposits::create([
                'user_id'             => $user->id,
                'amount_deposited'    => $resolved['amount'],
                'amount_removed'      => 0,
                'currency_type'       => 'USDT',
                'deposit_method'      => self::DEPOSIT_METHOD,
                'payment_context'     => self::PAYMENT_CONTEXT,
                'package_type'        => $resolved['type'],
                'package_id'          => $resolved['id'],
                'package_name'        => $resolved['name'],
                'transaction_id'      => Deposits::generateTransactionNo(),
                'deposit_address'     => $depositAddress->address,
                // Temporary value required by the production schema; replaced
                // with the actual sender wallet when the scanner sees payment.
                'user_wallet_address' => $depositAddress->address,
                'network'             => 'TRC-20',
                'status'              => 'pending',
                'expires_at'          => now()->addMinutes(self::PAYMENT_WINDOW_MINUTES),
                'comment'             => 'Direct USDT TRC20 package payment invoice. Send exact amount to the listed address within ' . self::PAYMENT_WINDOW_MINUTES . ' minutes.',
            ]);

            BlockchainAuditLog::record('package_payment.intent_created', [
                'user_id'        => $user->id,
                'auditable_type' => Deposits::class,
                'auditable_id'   => $deposit->id,
                'address'        => $depositAddress->address,
                'amount'         => $resolved['amount'],
                'currency'       => 'USDT',
                'network'        => 'TRC-20',
                'request_id'     => $deposit->transaction_id,
                'message'        => 'Direct USDT TRC20 package payment invoice created.',
                'context'        => [
                    'package_type' => $resolved['type'],
                    'package_id'   => $resolved['id'],
                    'package_name' => $resolved['name'],
                ],
            ]);

            return $deposit;
        });
    }

    public function activateFromDeposit(Deposits $deposit): ?Paymodel
    {
        if ($deposit->payment_context !== self::PAYMENT_CONTEXT) {
            return null;
        }

        return DB::transaction(function () use ($deposit) {
            /** @var Deposits $locked */
            $locked = Deposits::whereKey($deposit->id)->lockForUpdate()->first();
            if (!$locked) {
                return null;
            }

            if ($locked->activated_payment_id) {
                return Paymodel::find($locked->activated_payment_id);
            }

            if ($locked->status !== 'approved') {
                return null;
            }

            if ($this->isPaidAfterExpiry($locked)) {
                BlockchainAuditLog::record('package_payment.expired_not_activated', [
                    'level'         => 'warning',
                    'user_id'       => $locked->user_id,
                    'auditable_type'=> Deposits::class,
                    'auditable_id'  => $locked->id,
                    'tx_hash'       => $locked->blockchain_tx_hash,
                    'address'       => $locked->deposit_address,
                    'amount'        => (float) $locked->amount_deposited,
                    'currency'      => 'USDT',
                    'network'       => 'TRC-20',
                    'request_id'    => $locked->transaction_id,
                    'message'       => 'Direct package payment was received after the invoice expired; funds remain in DEPOSIT balance.',
                ]);
                return null;
            }

            $user = User::find($locked->user_id);
            if (!$user) {
                throw new \RuntimeException('Cannot activate package: user not found for deposit #' . $locked->id);
            }

            $amount = (float) $locked->amount_deposited;
            $type   = strtoupper((string) $locked->package_type);

            if ($type === 'VENTURE') {
                $payment = $this->activateVenture($user, $locked, $amount);
            } elseif ($type === 'FC') {
                $payment = $this->activateFc($user, $locked, $amount);
            } else {
                throw new \RuntimeException('Unsupported package type for direct payment: ' . $locked->package_type);
            }

            $usedDeposit = $this->createUsedDeposit($locked, $payment, $amount);
            $this->consumeDepositChartBalance($user->id, $amount);

            $locked->update([
                'activated_payment_id' => $payment->id,
                'activated_at'         => now(),
                'comment'              => trim(($locked->comment ? $locked->comment . "\n" : '') . 'Package activated automatically. Used deposit row #' . $usedDeposit->id . ', payment #' . $payment->id . '.'),
            ]);

            BlockchainAuditLog::record('package_payment.activated', [
                'user_id'        => $user->id,
                'auditable_type' => Paymodel::class,
                'auditable_id'   => $payment->id,
                'tx_hash'        => $locked->blockchain_tx_hash,
                'address'        => $locked->deposit_address,
                'amount'         => $amount,
                'currency'       => 'USDT',
                'network'        => 'TRC-20',
                'request_id'     => $locked->transaction_id,
                'message'        => 'Package activated automatically from direct USDT TRC20 payment.',
                'context'        => [
                    'deposit_id'      => $locked->id,
                    'used_deposit_id' => $usedDeposit->id,
                    'package_type'    => $type,
                    'package_id'      => $locked->package_id,
                    'package_name'    => $locked->package_name,
                ],
            ]);

            return $payment;
        });
    }

    public function resolvePackage(string $packageType, int $packageId, ?float $amount = null): array
    {
        $type = strtoupper(trim($packageType));

        if ($type === 'VENTURE' || $type === 'UVP') {
            $adventure = adventures::findOrFail($packageId);
            $investmentAmount = (float) $amount;

            if ($investmentAmount <= 0) {
                throw new \InvalidArgumentException('Please enter a valid UVP investment amount.');
            }

            $min = (float) ($adventure->min_amount ?? 0);
            $max = (float) ($adventure->max_amount ?? 0);
            if ($min > 0 && $investmentAmount < $min) {
                throw new \InvalidArgumentException('Minimum amount for this UVP package is $' . number_format($min, 2) . '.');
            }
            if ($max > 0 && $investmentAmount > $max) {
                throw new \InvalidArgumentException('Maximum amount for this UVP package is $' . number_format($max, 2) . '.');
            }

            return [
                'type'     => 'VENTURE',
                'id'       => (int) $adventure->id,
                'name'     => trim('UVP ' . ($adventure->name ?: $adventure->plan)),
                'amount'   => round($investmentAmount, 2),
                'duration' => (int) $adventure->duration,
                'model'    => $adventure,
            ];
        }

        if ($type === 'FC') {
            $package = FCpackage::findOrFail($packageId);
            $price = round((float) $package->price, 2);

            if ($price <= 0) {
                throw new \InvalidArgumentException('This FC package has an invalid price.');
            }

            return [
                'type'     => 'FC',
                'id'       => (int) $package->id,
                'name'     => $package->name ?: ('FC VIP $' . number_format($price, 2)),
                'amount'   => $price,
                'duration' => 100,
                'model'    => $package,
            ];
        }

        throw new \InvalidArgumentException('Unsupported package type: ' . $packageType);
    }

    private function activateVenture(User $user, Deposits $deposit, float $amount): Paymodel
    {
        $adventure = adventures::findOrFail((int) $deposit->package_id);

        $payment = InvestmentFactory::buildVenture(
            userId:    $user->id,
            adventure: $adventure,
            amount:    $amount,
            paid:      $amount,
            status:    1
        );

        $payment = $adventure->payments()->save($payment);

        $this->creditVentureTokens($user, $amount);
        $this->createVentureSubscriptionTransaction($user, $adventure, $payment, $deposit, $amount);
        $this->creditReferralEarnings($payment);

        $user->update([
            'has_paid_package' => $adventure->name,
            'has_free_package' => 'no',
        ]);

        return $payment;
    }

    private function activateFc(User $user, Deposits $deposit, float $amount): Paymodel
    {
        $package = FCpackage::findOrFail((int) $deposit->package_id);
        $expireAt = Carbon::now()->addDays(100);

        $payment = $user->investments()
            ->where('is_expired', 0)
            ->where('status', 1)
            ->where('category', 'FC')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$payment) {
            $createPayable = new Paymodel([
                'user'            => $user->id,
                'package'         => $package->name,
                'amount'          => $package->price,
                'paid'            => $amount,
                'over_paid'       => 0,
                'status'          => 1,
                'expiration_date' => $expireAt,
                'duration'        => 100,
                'category'        => 'FC',
                'category_id'     => 0,
                'is_expired'      => false,
            ]);

            $payment = $package->payments()->save($createPayable);
        } else {
            $newAmount = (float) $payment->amount + (float) $package->price;
            $payment->update([
                'package'         => $package->name,
                'amount'          => $newAmount,
                'paid'            => $newAmount,
                'expiration_date' => $expireAt,
                'duration'        => 100,
                'category'        => 'FC',
                'status'          => 1,
            ]);
            $payment->refresh();
        }

        $user->update([
            'has_free_package' => 'no',
            'has_paid_package' => $package->name,
        ]);

        $this->createFcSubscriptionTransaction($user, $package, $payment, $deposit, $amount);

        return $payment;
    }

    private function isPaidAfterExpiry(Deposits $deposit): bool
    {
        if (!$deposit->expires_at) {
            return false;
        }

        $paidAt = $deposit->detected_at ?: $deposit->credited_at ?: now();

        return $paidAt->greaterThan($deposit->expires_at);
    }

    private function createUsedDeposit(Deposits $sourceDeposit, Paymodel $payment, float $amount): Deposits
    {
        return Deposits::create([
            'user_id'             => $sourceDeposit->user_id,
            'amount_deposited'    => 0,
            'amount_removed'      => $amount,
            'currency_type'       => 'USDT',
            'deposit_method'      => self::USED_METHOD,
            'payment_context'     => self::PAYMENT_CONTEXT,
            'package_type'        => $sourceDeposit->package_type,
            'package_id'          => $sourceDeposit->package_id,
            'package_name'        => $sourceDeposit->package_name,
            'activated_payment_id'=> $payment->id,
            'activated_at'        => now(),
            'transaction_id'      => Deposits::generateTransactionNo(),
            'network'             => $sourceDeposit->network ?: 'TRC-20',
            'user_wallet_address' => $sourceDeposit->user_wallet_address ?: ($sourceDeposit->deposit_address ?: ''),
            'deposit_address'     => $sourceDeposit->deposit_address,
            'status'              => 'used',
            'comment'             => 'Auto-consumed for package payment from deposit #' . $sourceDeposit->id . '.',
        ]);
    }

    private function consumeDepositChartBalance(int $userId, float $amount): void
    {
        $existing = (float) ChartAccount::where('user_id', $userId)->where('acc_type', 'DEPOSIT')->sum('amount');

        ChartAccount::updateOrCreate(
            ['user_id' => $userId, 'acc_type' => 'DEPOSIT'],
            ['amount'  => max(0, $existing - $amount)]
        );
    }

    private function creditVentureTokens(User $user, float $amount): void
    {
        $uvpPrice = TokenSetting::uvpPrice();
        if ($uvpPrice <= 0) {
            return;
        }

        $lockedTokens = round($amount / $uvpPrice, 4);
        $gasFeeTokens = round(($amount * 20 / 100) / $uvpPrice, 4);

        $existingLocked = (float) $user->ChartAccount()->where('acc_type', 'LOCKED_TOKEN')->sum('amount');
        ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'LOCKED_TOKEN'],
            ['amount'  => $existingLocked + $lockedTokens]
        );

        $existingGas = (float) $user->ChartAccount()->where('acc_type', 'GAS_FEE')->sum('amount');
        ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'GAS_FEE'],
            ['amount'  => $existingGas + $gasFeeTokens]
        );
    }

    private function createVentureSubscriptionTransaction(User $user, adventures $adventure, Paymodel $payment, Deposits $deposit, float $amount): void
    {
        $startDate = Carbon::now()->addHours(24);
        $endDate = $startDate->copy()->addDays((int) $adventure->duration);
        $uvpPrice = TokenSetting::uvpPrice();

        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => Transaction::generateTransactionNo(),
            'transaction_type'    => 'SUBSCRIPTION',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'product'            => $adventure->name,
                'user'               => $user->name,
                'plan'               => $adventure->plan,
                'start_date'         => $startDate,
                'end_date'           => $endDate,
                'package'            => $adventure->plan,
                'price'              => $amount,
                'current_price'      => $amount,
                'token'              => $uvpPrice > 0 ? round($amount / $uvpPrice, 4) : 0,
                'current_token'      => $uvpPrice > 0 ? round($amount / $uvpPrice, 4) : 0,
                'poolcapital'        => $amount * 80 / 100,
                'current_poolcapital'=> $amount * 80 / 100,
                'LP'                 => $amount * 20 / 100,
                'current_LP'         => $amount * 20 / 100,
                'period'             => $adventure->duration . ' days',
                'revenue_earned'     => 0,
                'revenue_type'       => 'soon',
                'status'             => 'success',
                'purchase_date'      => $startDate,
                'username'           => $user->user,
                'payment_method'     => self::DEPOSIT_METHOD,
                'deposit_id'         => $deposit->id,
                'blockchain_tx_hash' => $deposit->blockchain_tx_hash,
                'payment_id'         => $payment->id,
            ]),
        ]);
    }

    private function createFcSubscriptionTransaction(User $user, FCpackage $package, Paymodel $payment, Deposits $deposit, float $amount): void
    {
        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => Transaction::generateTransactionNo(),
            'transaction_type'    => 'SUBSCRIPTION',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'product'            => $package->name,
                'user'               => $user->email,
                'plan'               => 'soon',
                'start_date'         => Carbon::now(),
                'end_date'           => Carbon::now()->addDays(100),
                'package'            => $package->name,
                'price'              => $package->price,
                'token'              => $package->default_token,
                'current_price'      => $payment->amount,
                'poolcapital'        => 0,
                'current_poolcapital'=> 0,
                'LP'                 => 0,
                'current_LP'         => 0,
                'period'             => '100 days',
                'revenue_earned'     => 0,
                'revenue_type'       => 'soon',
                'status'             => 'success',
                'purchase_date'      => Carbon::now(),
                'username'           => $user->name,
                'payment_method'     => self::DEPOSIT_METHOD,
                'deposit_id'         => $deposit->id,
                'blockchain_tx_hash' => $deposit->blockchain_tx_hash,
                'payment_id'         => $payment->id,
                'amount_paid'        => $amount,
            ]),
        ]);
    }

    private function creditReferralEarnings(Paymodel $payment): void
    {
        $bonusRows = ReferralService::creditForPayment($payment);

        foreach ($bonusRows as $row) {
            $referrer = User::find($row->user_id);
            if (!$referrer) {
                continue;
            }

            Earnings::updateOrCreate(
                [
                    'user_id'     => $referrer->id,
                    'source_id'   => $payment->id,
                    'source_type' => Paymodel::class,
                ],
                ['amount' => (float) $row->bonus_amount]
            );

            $existing = (float) ChartAccount::where('user_id', $referrer->id)
                ->where('acc_type', 'COMMISSION')
                ->sum('amount');

            ChartAccount::updateOrCreate(
                ['user_id' => $referrer->id, 'acc_type' => 'COMMISSION'],
                ['amount'  => $existing + (float) $row->bonus_amount]
            );

            $description = match ((int) $row->level) {
                1       => 'Direct Referral Commission (10%)',
                2       => 'Indirect Referral Commission (1%)',
                3       => '3rd-Level Referral Commission (0.5%)',
                default => 'Referral Commission L' . $row->level,
            };

            Transaction::create([
                'user_id'             => $referrer->id,
                'transaction_no'      => Transaction::generateTransactionNo(),
                'transaction_type'    => 'COMMISSION',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'amount'      => (float) $row->bonus_amount,
                    'description' => $description,
                    'level'       => $row->level,
                    'percentage'  => (float) $row->percentage,
                    'source'      => $payment->user,
                    'week_start'  => $row->week_start->toDateString(),
                    'username'    => $referrer->name,
                ]),
            ]);
        }
    }
}
