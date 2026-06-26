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
- Automatic payout is done by a local Node.js signer service using TronWeb.

### Security

- Signer service binds to `127.0.0.1`.
- Signer requires IP whitelist + `X-Signer-Secret`.
- Hot-wallet private key is used only by the signer service.
- Hot-wallet sweep command can move excess USDT to cold wallet.
- Blockchain audit logs are stored.

## Install/update

```bash
composer install
npm install
php artisan migrate
```

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
TRON_SIGNER_SECRET=long_random_secret
TRON_SIGNER_URL=http://127.0.0.1:8787
DIRECT_BLOCKCHAIN_WITHDRAWALS=true
QUEUE_CONNECTION=database
```

## Start signer

```bash
npm run signer:start
```

Production recommendation: run `npm run signer:start` with Supervisor, systemd, or PM2. Keep it bound to localhost/private network.

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
