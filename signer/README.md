# TRON Signer Service

Small local Node.js service used by Laravel for:

- generating unique TRON deposit addresses per user
- signing/broadcasting USDT TRC20 withdrawals from the hot wallet

It binds to `127.0.0.1` and requires both an IP whitelist and `X-Signer-Secret`.

## Install

From the project root:

```bash
npm install
```
# Run this inside your project folder:
```bash
npm pkg set scripts.signer:start="node signer/server.js"
npm install express dotenv tronweb
npm run signer:start
```

# If it works, you should see something like:
```bash
TRON signer listening on http://127.0.0.1:87
```

## Required `.env`

```env
TRON_NETWORK=mainnet
TRON_FULL_HOST=https://api.trongrid.io
TRONGRID_API_KEY=your_trongrid_api_key
TRON_USDT_CONTRACT=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t

TRON_HOT_WALLET_ADDRESS=T...
TRON_HOT_WALLET_PRIVATE_KEY=never_commit_this

TRON_SIGNER_URL=http://127.0.0.1:8787
TRON_SIGNER_PORT=8787
TRON_SIGNER_SECRET=use_a_long_random_secret
TRON_SIGNER_ALLOWED_IPS=127.0.0.1,::1,::ffff:127.0.0.1
DIRECT_BLOCKCHAIN_WITHDRAWALS=true
```

## Start

```bash
npm run signer:start
```

For production, run it with Supervisor/systemd/PM2 and keep it bound to localhost or a private network only.

## Security notes

- Never expose this service publicly.
- Never put the hot-wallet private key in frontend JS or the database.
- Keep only operational funds in the hot wallet.
- Configure a cold wallet + sweep threshold in Laravel settings/env.
