'use strict';

require('dotenv').config();
const express = require('express');
const TronWebPackage = require('tronweb');

const TronWeb = TronWebPackage.TronWeb || TronWebPackage;

const app = express();
app.set('trust proxy', process.env.SIGNER_TRUST_PROXY === 'true');
app.use(express.json({ limit: '64kb' }));

const PORT = Number(process.env.TRON_SIGNER_PORT || 8787);
const FULL_HOST = process.env.TRON_FULL_HOST || 'https://api.trongrid.io';
const PRIVATE_KEY = process.env.TRON_HOT_WALLET_PRIVATE_KEY;
const USDT_CONTRACT = process.env.TRON_USDT_CONTRACT || 'TKu83PAfPbGd6KmoUx8dmST2qpdb2nnV8w';
const SIGNER_SECRET = process.env.TRON_SIGNER_SECRET;
const FEE_LIMIT = Number(process.env.TRON_USDT_FEE_LIMIT || 100000000); // 100 TRX in SUN
const ALLOWED_IPS = (process.env.TRON_SIGNER_ALLOWED_IPS || '127.0.0.1,::1,::ffff:127.0.0.1')
  .split(',')
  .map(v => v.trim())
  .filter(Boolean);

function normalizeIp(ip) {
  if (!ip) return '';
  return ip.replace('::ffff:', '');
}

function clientIp(req) {
  const forwarded = (req.headers['x-forwarded-for'] || '').split(',')[0].trim();
  return normalizeIp(forwarded || req.ip || req.socket.remoteAddress);
}

function requireIpAndSecret(req, res, next) {
  const ip = clientIp(req);
  const ipOk = ALLOWED_IPS.includes(ip) || ALLOWED_IPS.includes(req.ip) || ALLOWED_IPS.includes('*');
  if (!ipOk) {
    return res.status(403).json({ ok: false, message: `IP not allowed: ${ip}` });
  }

  if (!SIGNER_SECRET || req.header('X-Signer-Secret') !== SIGNER_SECRET) {
    return res.status(401).json({ ok: false, message: 'Invalid signer secret' });
  }

  next();
}

function tronWebWithHotWallet() {
  if (!PRIVATE_KEY) {
    throw new Error('TRON_HOT_WALLET_PRIVATE_KEY is not configured');
  }

  return new TronWeb({
    fullHost: FULL_HOST,
    privateKey: PRIVATE_KEY,
    headers: process.env.TRONGRID_API_KEY ? { 'TRON-PRO-API-KEY': process.env.TRONGRID_API_KEY } : undefined,
  });
}

function tronWebNoKey() {
  return new TronWeb({
    fullHost: FULL_HOST,
    headers: process.env.TRONGRID_API_KEY ? { 'TRON-PRO-API-KEY': process.env.TRONGRID_API_KEY } : undefined,
  });
}

function assertTronAddress(tronWeb, address) {
  const isAddress = typeof tronWeb.isAddress === 'function'
    ? tronWeb.isAddress.bind(tronWeb)
    : TronWeb.isAddress;
  if (!isAddress(address)) {
    throw new Error('Invalid TRON address');
  }
}

function amountToUsdtInteger(amount) {
  const n = Number(amount);
  if (!Number.isFinite(n) || n <= 0) {
    throw new Error('Invalid amount');
  }

  return Math.round(n * 1_000_000);
}

app.get('/health', (req, res) => {
  res.json({ ok: true, fullHost: FULL_HOST, contract: USDT_CONTRACT });
});

app.post('/address/generate', requireIpAndSecret, async (req, res) => {
  try {
    const tronWeb = tronWebNoKey();
    const account = typeof tronWeb.createAccount === 'function'
      ? await tronWeb.createAccount()
      : await TronWeb.createAccount();
    res.json({
      ok: true,
      requestId: req.body.requestId || null,
      address: account.address.base58,
      hexAddress: account.address.hex,
      privateKey: account.privateKey,
    });
  } catch (err) {
    res.status(500).json({ ok: false, message: err.message });
  }
});

app.post('/usdt/send', requireIpAndSecret, async (req, res) => {
  try {
    const { to, amount, requestId } = req.body || {};
    const tronWeb = tronWebWithHotWallet();
    assertTronAddress(tronWeb, to);

    const amountInt = amountToUsdtInteger(amount);
    const contract = await tronWeb.contract().at(USDT_CONTRACT);
    const txid = await contract.transfer(to, amountInt.toString()).send({ feeLimit: FEE_LIMIT });

    res.json({
      ok: true,
      requestId: requestId || null,
      txid,
      to,
      amount: Number(amount),
      contract: USDT_CONTRACT,
    });
  } catch (err) {
    res.status(500).json({ ok: false, message: err.message });
  }
});

app.listen(PORT, '127.0.0.1', () => {
  console.log(`TRON signer listening on http://127.0.0.1:${PORT}`);
  console.log(`Allowed IPs: ${ALLOWED_IPS.join(', ')}`);
});
