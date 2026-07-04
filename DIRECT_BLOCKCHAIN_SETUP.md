# Direct TRON USDT TRC20 Integration

This project now supports backend-only blockchain automation without TronLink.

## What was added

### Deposits

- Unique USDT TRC20 deposit address per user.
- TronGrid scanner checks each active address.
- Incoming confirmed USDT TRC20 transfers are credited automatically to the user's `DEPOSIT` `ChartAccount`. Deposits are not withdrawable; withdrawals use `CASHOUT` only.
- Duplicate crediting is blocked by on-chain transaction hash.
- Deposit audit logs are written to `blockchain_audit_logs`.

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
TRON_USDT_CONTRACT=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t
TRON_HOT_WALLET_ADDRESS=T...
TRON_HOT_WALLET_PRIVATE_KEY=...
TRON_USDT_FEE_LIMIT_TRX=50
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

# Check hot-wallet sweep without sending
php artisan blockchain:sweep-hot-wallet --dry-run

# Sweep excess USDT from hot wallet to cold wallet
php artisan blockchain:sweep-hot-wallet
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

## Important operational note about unique deposit addresses

Unique user deposit addresses make auto-crediting accurate. However, USDT sent to those addresses remains on those addresses until you sweep/consolidate it. Sweeping USDT from a deposit address requires TRX for network fees on that address. Keep this in mind for treasury operations.

Never share or commit private keys or seed phrases.
