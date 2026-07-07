# Direct TRON USDT TRC20 Integration

This project now supports backend-only blockchain automation without TronLink.

## What was added

### Deposits

- Unique USDT TRC20 deposit address per user.
- TronGrid scanner checks each active address.
- Incoming confirmed USDT TRC20 transfers are credited automatically to the user's `DEPOSIT` `ChartAccount`. Deposits are not withdrawable; withdrawals use `CASHOUT` only.
- Duplicate crediting is blocked by on-chain transaction hash.
- Deposit audit logs are written to `blockchain_audit_logs`.
- Deposit-address sweep command consolidates USDT from unique user deposit addresses into the hot wallet.

### Withdrawals

- User withdrawal requests still hold/deduct funds first.
- Limits are enforced: min, max per transaction, daily, monthly.
- Duplicate requests are blocked for same user/address/amount in a 10-minute window.
- Risk scoring sends suspicious requests to manual review.
- Small USDT TRC20 withdrawals can be queued for automatic blockchain payout.
- Large/suspicious/non-USDT withdrawals require admin approval.
- Admin can either:
  - approve and queue automatic TRON payout, or
  - enter a tx hash after manual payout and mark completed.
- Automatic payout is signed and broadcast directly in PHP (no external signer process).

### Security

- Address generation and USDT transfer signing are done with a pure-PHP
  TRON SDK (`iexbase/tron-api`) directly inside Laravel. There is no
  separate Node.js "signer" process and no local port to keep open, so
  this works on ordinary shared hosting (cPanel, etc.) with no SSH/Node
  support required.
- The hot-wallet private key lives only in `.env` (`TRON_HOT_WALLET_PRIVATE_KEY`)
  and is read only by `App\Services\TronBlockchainService`. Never commit it,
  never expose it in any public route/response.
- Deposit-address sweep command can auto-fund low-TRX deposit addresses from a dedicated TRX fee wallet before sweeping USDT.
- Hot-wallet sweep command can move excess USDT to cold wallet.
- Blockchain audit logs are stored.

## Install/update

```bash
composer install
npm install
php artisan migrate
```

If your host has no SSH/terminal access (typical on shared cPanel hosting),
run `composer install` on your local machine instead, then upload the
resulting `vendor/` folder (and the rest of the project) via File Manager/FTP.
`ext-bcmath` is required and is enabled by default on virtually all cPanel
PHP builds; you can confirm it under cPanel → MultiPHP INI Editor / Select
PHP Extensions.

If using database queues:

```bash
php artisan queue:work --queue=default --tries=3
```

Run the scheduler from cron:

```cron
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Environment

Copy values from `.env.blockchain.example` into `.env` and fill them in.

Required mainnet values:

```env
TRON_NETWORK=mainnet
TRON_FULL_HOST=https://api.trongrid.io
TRONGRID_API_KEY=your_trongrid_api_key
TRON_USDT_CONTRACT=TKu83PAfPbGd6KmoUx8dmST2qpdb2nnV8w
TRON_HOT_WALLET_ADDRESS=T...
TRON_HOT_WALLET_PRIVATE_KEY=...
TRON_FEE_WALLET_ADDRESS=T...
TRON_FEE_WALLET_PRIVATE_KEY=...
TRON_USDT_FEE_LIMIT_TRX=50
TRON_DEPOSIT_SWEEP_MIN_USDT=10
TRON_DEPOSIT_SWEEP_MIN_TRX=20
TRON_AUTO_FUND_DEPOSIT_ADDRESSES=false
TRON_DEPOSIT_SWEEP_TARGET_TRX=25
TRON_MAX_TRX_FUND_PER_ADDRESS=30
TRON_MAX_TRX_FUND_PER_RUN=200
TRON_FEE_WALLET_MIN_RESERVE_TRX=50
TRON_HOT_WALLET_MIN_TRX_FOR_SWEEP=20
DIRECT_BLOCKCHAIN_WITHDRAWALS=true
QUEUE_CONNECTION=database
```

There is no separate signer service to start — deposit address generation
and withdrawal signing both happen inline in PHP the first time they're
needed.

## Commands

```bash
# Scan deposits now
php artisan blockchain:check-deposits

# Dispatch queued withdrawals
php artisan blockchain:process-withdrawals

# Check deposit-address consolidation without sending
php artisan blockchain:sweep-deposit-addresses --dry-run

# Check one deposit address / DB id only, useful after a test deposit
php artisan blockchain:sweep-deposit-addresses --dry-run --address=T_USER_DEPOSIT_ADDRESS
php artisan blockchain:sweep-deposit-addresses --address=T_USER_DEPOSIT_ADDRESS --min-usdt=0.01

# Preview TRX auto-funding for low-TRX deposit addresses
php artisan blockchain:sweep-deposit-addresses --dry-run --fund-trx

# Auto-fund low-TRX deposit addresses from the fee wallet, then sweep on the next run after confirmation
php artisan blockchain:sweep-deposit-addresses --fund-trx

# Sweep/consolidate funded unique deposit addresses into the hot wallet
php artisan blockchain:sweep-deposit-addresses

# Check hot-wallet sweep without sending
php artisan blockchain:sweep-hot-wallet --dry-run

# Test a small cold-wallet sweep without using the automatic max/reserve amount
php artisan blockchain:sweep-hot-wallet --dry-run --amount=1
php artisan blockchain:sweep-hot-wallet --amount=1

# Sweep excess USDT from hot wallet to cold wallet
php artisan blockchain:sweep-hot-wallet
```

## Cold wallet safety

The cold wallet is a receiving-only treasury wallet. It must be a valid TRON address and must not be the same as the hot wallet or the USDT token contract address.

The hot-wallet-to-cold-wallet command now validates:

- hot wallet address format
- cold wallet address format
- cold wallet is different from hot wallet
- cold wallet is not the USDT contract address
- hot wallet has enough TRX for the TRC20 transfer fee
- sweep amount does not reduce the hot wallet below reserve

For a first production test, use:

```bash
php artisan blockchain:sweep-hot-wallet --dry-run --amount=1
php artisan blockchain:sweep-hot-wallet --amount=1
```

## Admin settings

Go to:

`/admin/settings/withdrawal-settings`

Important fields:

- `Require admin approval for all withdrawals`: safest mode. If enabled, all withdrawals wait for admin.
- `Enable small automatic USDT TRC20 withdrawals`: allows eligible small withdrawals to go directly to queue.
- `Admin approval threshold`: withdrawals at/above this amount require admin.
- `Max automatic withdrawal`: upper cap for automatic payout.
- `Manual review risk score`: risk score at/above this goes to admin.
- `Hot wallet max USDT`, `Hot wallet reserve USDT`, `Cold wallet address`: used by the sweep command.

## Package-payment amount handling

For direct package-payment invoices, exact payments activate normally.

If a user overpays, for example invoice is `10 USDT` but the on-chain transfer is `15 USDT`, the scanner now matches the invoice, credits the full `15 USDT` to the user's `DEPOSIT` balance, activates the package using the invoice amount `10 USDT`, and leaves the extra `5 USDT` available in the user's `DEPOSIT` balance. An audit log event `package_payment.overpaid` is written.

If a user underpays, for example invoice is `10 USDT` but the transfer is `8 USDT`, the package invoice is not activated. The `8 USDT` is credited as normal `DEPOSIT` balance and the package invoice remains pending until it expires or the user creates/pays another invoice.

## Important operational note about unique deposit addresses

Unique user deposit addresses make auto-crediting accurate. However, USDT sent to those addresses remains on those addresses until you sweep/consolidate it.

This project includes:

```bash
php artisan blockchain:sweep-deposit-addresses
```

That command sends USDT from each funded unique deposit address into `TRON_HOT_WALLET_ADDRESS`. It uses the encrypted private key stored in `blockchain_deposit_addresses.encrypted_private_key` and writes audit logs to `blockchain_audit_logs`.

Important:

- Sweeping USDT from a deposit address requires TRX on that deposit address for TRON network fees.
- The command will skip addresses below `TRON_DEPOSIT_SWEEP_MIN_USDT`.
- If an address has enough USDT but less than `TRON_DEPOSIT_SWEEP_MIN_TRX`, the command logs `deposit_address.sweep_needs_trx`.
- If `TRON_AUTO_FUND_DEPOSIT_ADDRESSES=true` or you pass `--fund-trx`, the command sends TRX from `TRON_FEE_WALLET_ADDRESS` to that deposit address up to `TRON_DEPOSIT_SWEEP_TARGET_TRX`.
- After TRX auto-funding, the command waits for confirmation and sweeps USDT on the next scheduled run.
- Auto-funding is protected by `TRON_MAX_TRX_FUND_PER_ADDRESS`, `TRON_MAX_TRX_FUND_PER_RUN`, and `TRON_FEE_WALLET_MIN_RESERVE_TRX`.
- For a test, run:

```bash
# Preview only
php artisan blockchain:sweep-deposit-addresses --dry-run --fund-trx --address=T_USER_DEPOSIT_ADDRESS --min-usdt=0.01

# Send TRX from the fee wallet if needed
php artisan blockchain:sweep-deposit-addresses --fund-trx --address=T_USER_DEPOSIT_ADDRESS --min-usdt=0.01

# After TRX confirms, sweep USDT
php artisan blockchain:sweep-deposit-addresses --address=T_USER_DEPOSIT_ADDRESS --min-usdt=0.01
```

Never share or commit private keys or seed phrases.
