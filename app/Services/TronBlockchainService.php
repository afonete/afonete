<?php

namespace App\Services;

use App\Models\BlockchainAuditLog;
use App\Models\BlockchainDepositAddress;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Exception\TronException;

/**
 * Direct TRON Blockchain Integration (TRC20 USDT).
 *
 * TronGrid is used for chain reads / transaction monitoring.
 *
 * Key generation and signing are done with a pure-PHP TRON SDK
 * (iexbase/tron-api) so this works on ordinary shared hosting
 * (cPanel, etc.) without a separate Node.js "signer" process.
 * No external signer HTTP service, no long-running daemon,
 * no custom localhost port required.
 */
class TronBlockchainService
{
    protected ?string $apiKey;
    protected string $baseUrl;
    protected string $usdtContract;
    protected ?string $hotWalletAddress;
    protected ?string $hotWalletPrivateKey;
    protected ?string $feeWalletAddress;
    protected ?string $feeWalletPrivateKey;
    protected int $feeLimitTrx;

    public function __construct()
    {
        $this->apiKey              = config('services.tron.api_key') ?: env('TRONGRID_API_KEY');
        $this->baseUrl             = rtrim(config('services.tron.base_url') ?: env('TRON_FULL_HOST', 'https://api.trongrid.io'), '/');
        $this->usdtContract        = config('services.tron.usdt_contract') ?: env('TRON_USDT_CONTRACT', 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');
        $this->hotWalletAddress    = config('services.tron.hot_wallet') ?: env('TRON_HOT_WALLET_ADDRESS');
        $this->hotWalletPrivateKey = config('services.tron.hot_wallet_private_key') ?: env('TRON_HOT_WALLET_PRIVATE_KEY');
        $this->feeWalletAddress    = config('services.tron.fee_wallet') ?: env('TRON_FEE_WALLET_ADDRESS');
        $this->feeWalletPrivateKey = config('services.tron.fee_wallet_private_key') ?: env('TRON_FEE_WALLET_PRIVATE_KEY');
        $this->feeLimitTrx         = (int) (config('services.tron.fee_limit_trx') ?: env('TRON_USDT_FEE_LIMIT_TRX', 50));
    }

    /**
     * Build a Tron client instance pointed at TronGrid, optionally
     * with a private key loaded for signing.
     */
    protected function tronClient(?string $privateKey = null): Tron
    {
        $headers = $this->apiKey ? ['TRON-PRO-API-KEY' => $this->apiKey] : [];

        $fullNode     = new HttpProvider($this->baseUrl, 30000, false, false, $headers);
        $solidityNode = new HttpProvider($this->baseUrl, 30000, false, false, $headers);
        $eventServer  = new HttpProvider($this->baseUrl, 30000, false, false, $headers);

        $tron = new Tron($fullNode, $solidityNode, $eventServer);

        if ($privateKey) {
            $tron->setPrivateKey($privateKey);
        }

        return $tron;
    }

    public function getHotWalletAddress(): ?string
    {
        return $this->hotWalletAddress;
    }

    public function getFeeWalletAddress(): ?string
    {
        return $this->feeWalletAddress;
    }

    public function getUsdtContract(): string
    {
        return $this->usdtContract;
    }

    /** Get/create the user's unique USDT TRC20 deposit address. */
    public function getOrCreateDepositAddressForUser(User $user): ?BlockchainDepositAddress
    {
        $existing = BlockchainDepositAddress::where('user_id', $user->id)
            ->where('currency', 'USDT')
            ->where('network', 'TRC-20')
            ->where('is_active', true)
            ->first();

        if ($existing) {
            return $existing;
        }

        try {
            $generated = $this->generateAddress();
            if (empty($generated['address']) || empty($generated['privateKey'])) {
                throw new \RuntimeException('Signer did not return address/privateKey.');
            }

            $address = new BlockchainDepositAddress([
                'user_id'   => $user->id,
                'currency'  => 'USDT',
                'network'   => 'TRC-20',
                'address'   => $generated['address'],
                'is_active' => true,
                'metadata'  => [
                    'source'     => 'tron_signer',
                    'created_by' => 'auto',
                ],
            ]);
            $address->private_key = $generated['privateKey'];
            $address->save();

            BlockchainAuditLog::record('deposit_address.created', [
                'user_id'  => $user->id,
                'address'  => $address->address,
                'currency' => 'USDT',
                'network'  => 'TRC-20',
                'message'  => 'Unique TRON deposit address created for user.',
            ]);

            return $address;
        } catch (\Throwable $e) {
            Log::error('Could not create TRON deposit address: ' . $e->getMessage(), ['user_id' => $user->id]);
            BlockchainAuditLog::record('deposit_address.create_failed', [
                'level'   => 'error',
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /** Generate a fresh TRON keypair locally in PHP (no external signer needed). */
    public function generateAddress(): array
    {
        try {
            $tron = $this->tronClient();
            $account = $tron->generateAddress();

            return [
                'ok'         => true,
                'address'    => $account->getAddress(true),
                'hexAddress' => $account->getAddress(false),
                'privateKey' => $account->getPrivateKey(),
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException('Address generation failed: ' . $e->getMessage());
        }
    }

    /** Get USDT TRC20 balance of an address. */
    public function getUsdtBalance(string $address): float
    {
        try {
            $response = $this->tronGet("/v1/accounts/{$address}");
            if ($response->successful()) {
                $data = $response->json('data.0');
                foreach (($data['trc20'] ?? []) as $token) {
                    if (isset($token[$this->usdtContract])) {
                        return (float) $token[$this->usdtContract] / 1_000_000;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('TRON USDT balance check failed: ' . $e->getMessage(), ['address' => $address]);
        }

        return 0.0;
    }

    /** Get TRX balance for fee-management / hot-wallet monitoring. */
    public function getTrxBalance(string $address): float
    {
        try {
            $response = $this->tronPost('/wallet/getaccount', [
                'address' => $address,
                'visible' => true,
            ]);

            if ($response->successful()) {
                return ((float) ($response->json('balance') ?? 0)) / 1_000_000;
            }
        } catch (\Throwable $e) {
            Log::error('TRON TRX balance check failed: ' . $e->getMessage(), ['address' => $address]);
        }

        return 0.0;
    }

    /** Get recent USDT TRC20 transactions for one address. */
    public function getRecentUsdtTransactions(string $address, int $limit = 50, ?int $minTimestamp = null): array
    {
        try {
            $query = [
                'limit'            => $limit,
                'contract_address' => $this->usdtContract,
                'only_confirmed'   => 'true',
                'order_by'         => 'block_timestamp,desc',
            ];

            if ($minTimestamp) {
                $query['min_timestamp'] = $minTimestamp;
            }

            $response = $this->tronGet("/v1/accounts/{$address}/transactions/trc20", $query);
            if ($response->successful()) {
                return $response->json('data', []);
            }

            Log::warning('TRON transaction fetch returned non-success response', [
                'address' => $address,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('TRON tx fetch failed: ' . $e->getMessage(), ['address' => $address]);
        }

        return [];
    }

    /** Send native TRX from the configured fee wallet to a deposit address. */
    public function sendTrxFromFeeWallet(string $toAddress, float $amount, ?string $requestId = null): array
    {
        if (!$this->feeWalletAddress) {
            throw new \RuntimeException('TRON_FEE_WALLET_ADDRESS is not configured.');
        }
        if (!$this->feeWalletPrivateKey) {
            throw new \RuntimeException('TRON_FEE_WALLET_PRIVATE_KEY is not configured.');
        }

        return $this->sendTrxFromPrivateKey(
            $this->feeWalletAddress,
            $this->feeWalletPrivateKey,
            $toAddress,
            $amount,
            $requestId ?: 'trx-fund-' . (string) Str::uuid()
        );
    }

    /** Send native TRX from any locally-controlled TRON address. */
    public function sendTrxFromPrivateKey(
        string $fromAddress,
        string $privateKey,
        string $toAddress,
        float $amount,
        ?string $requestId = null
    ): array {
        $fromAddress = trim($fromAddress);
        $toAddress = trim($toAddress);
        $privateKey = trim($privateKey);

        if ($fromAddress === '') {
            throw new \RuntimeException('Source TRON address is required.');
        }
        if ($toAddress === '') {
            throw new \RuntimeException('Destination TRON address is required.');
        }
        if ($privateKey === '') {
            throw new \RuntimeException('Source private key is required.');
        }
        if ($amount <= 0) {
            throw new \RuntimeException('TRX amount must be greater than zero.');
        }

        $requestId = $requestId ?: 'trx-send-' . (string) Str::uuid();
        $result = $this->signAndSendTrxFrom($fromAddress, $privateKey, $toAddress, $amount, $requestId);

        if (empty($result['txid']) && empty($result['txID'])) {
            throw new \RuntimeException($result['message'] ?? 'TRX transfer did not return a transaction hash.');
        }

        return [
            'txid'      => $result['txid'] ?? $result['txID'],
            'requestId' => $requestId,
            'raw'       => $result,
        ];
    }

    /** Send USDT TRC20 from the configured hot wallet, signed locally in PHP. */
    public function sendUsdt(string $toAddress, float $amount, ?string $requestId = null): array
    {
        if (!$this->hotWalletAddress) {
            throw new \RuntimeException('TRON_HOT_WALLET_ADDRESS is not configured.');
        }
        if (!$this->hotWalletPrivateKey) {
            throw new \RuntimeException('TRON_HOT_WALLET_PRIVATE_KEY is not configured.');
        }

        $requestId = $requestId ?: (string) Str::uuid();

        $result = $this->signAndSendUsdt($toAddress, $amount, $requestId);

        if (empty($result['txid']) && empty($result['txHash'])) {
            throw new \RuntimeException($result['message'] ?? 'Signer did not return a transaction hash.');
        }

        return [
            'txid'      => $result['txid'] ?? $result['txHash'],
            'requestId' => $requestId,
            'raw'       => $result,
        ];
    }

    /**
     * Send USDT TRC20 from any locally-controlled TRON address.
     *
     * Used for treasury consolidation: unique user deposit address -> hot wallet.
     * The source address must already hold enough TRX/Energy to pay the network fee.
     */
    public function sendUsdtFromPrivateKey(
        string $fromAddress,
        string $privateKey,
        string $toAddress,
        float $amount,
        ?string $requestId = null
    ): array {
        $fromAddress = trim($fromAddress);
        $toAddress = trim($toAddress);
        $privateKey = trim($privateKey);

        if ($fromAddress === '') {
            throw new \RuntimeException('Source TRON address is required.');
        }
        if ($toAddress === '') {
            throw new \RuntimeException('Destination TRON address is required.');
        }
        if ($privateKey === '') {
            throw new \RuntimeException('Source private key is required.');
        }
        if ($amount <= 0) {
            throw new \RuntimeException('Sweep amount must be greater than zero.');
        }

        $requestId = $requestId ?: 'deposit-sweep-' . (string) Str::uuid();
        $result = $this->signAndSendUsdtFrom(
            $fromAddress,
            $privateKey,
            $toAddress,
            $amount,
            $requestId
        );

        if (empty($result['txid']) && empty($result['txHash'])) {
            throw new \RuntimeException($result['message'] ?? 'TRON transfer did not return a transaction hash.');
        }

        return [
            'txid'      => $result['txid'] ?? $result['txHash'],
            'requestId' => $requestId,
            'raw'       => $result,
        ];
    }

    /** Sweep excess hot-wallet USDT to cold storage. */
    public function sweepHotWalletToCold(string $coldAddress, float $amount, ?string $requestId = null): array
    {
        return $this->sendUsdt($coldAddress, $amount, $requestId ?: 'sweep-' . (string) Str::uuid());
    }

    public function verifyUsdtTransferTo(string $txHash, string $expectedTo, ?float $expectedAmount = null): ?array
    {
        $hotWalletTransactions = $this->getRecentUsdtTransactions($expectedTo, 200);
        foreach ($hotWalletTransactions as $tx) {
            $hash = $tx['transaction_id'] ?? $tx['txID'] ?? null;
            if ($hash !== $txHash) {
                continue;
            }

            $amount = isset($tx['value']) ? ((float) $tx['value'] / 1_000_000) : 0.0;
            if ($expectedAmount !== null && abs($amount - $expectedAmount) > 0.000001) {
                return null;
            }

            return $tx;
        }

        return null;
    }

    protected function tronGet(string $path, array $query = [])
    {
        return Http::withHeaders($this->tronHeaders())->timeout(20)->get($this->baseUrl . $path, $query);
    }

    protected function tronPost(string $path, array $payload = [])
    {
        return Http::withHeaders($this->tronHeaders())->timeout(20)->post($this->baseUrl . $path, $payload);
    }

    protected function tronHeaders(): array
    {
        return array_filter([
            'TRON-PRO-API-KEY' => $this->apiKey,
            'Accept'           => 'application/json',
        ]);
    }

    /**
     * Sign and broadcast a native TRX transfer from an arbitrary TRON address.
     */
    protected function signAndSendTrxFrom(
        string $fromAddress,
        string $privateKey,
        string $toAddress,
        float $amount,
        string $requestId
    ): array {
        try {
            $tron = $this->tronClient($privateKey);

            if (!$tron->isAddress($fromAddress)) {
                throw new \RuntimeException('Invalid source TRON address: ' . $fromAddress);
            }
            if (!$tron->isAddress($toAddress)) {
                throw new \RuntimeException('Invalid destination TRON address: ' . $toAddress);
            }

            // sendTransaction() expects the amount in human-readable TRX and
            // converts it to SUN internally.
            $response = $tron->sendTransaction($toAddress, $amount, $fromAddress);

            $txid = $response['txID'] ?? $response['txid'] ?? null;
            if (!$txid) {
                throw new \RuntimeException('Broadcast did not return a transaction ID: ' . json_encode($response));
            }

            return [
                'ok'        => true,
                'txid'      => $txid,
                'requestId' => $requestId,
                'from'      => $fromAddress,
                'to'        => $toAddress,
                'amount'    => $amount,
                'asset'     => 'TRX',
                'raw'       => $response,
            ];
        } catch (TronException $e) {
            throw new \RuntimeException('TRX transfer failed: ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new \RuntimeException('TRX send failed: ' . $e->getMessage());
        }
    }

    /**
     * Sign and broadcast a USDT TRC20 transfer from the hot wallet, entirely
     * in PHP using the hot wallet's private key. No external signer process
     * is used, so this works on plain shared hosting.
     */
    protected function signAndSendUsdt(string $toAddress, float $amount, string $requestId): array
    {
        return $this->signAndSendUsdtFrom(
            (string) $this->hotWalletAddress,
            (string) $this->hotWalletPrivateKey,
            $toAddress,
            $amount,
            $requestId
        );
    }

    /**
     * Sign and broadcast a USDT TRC20 transfer from an arbitrary TRON address.
     */
    protected function signAndSendUsdtFrom(
        string $fromAddress,
        string $privateKey,
        string $toAddress,
        float $amount,
        string $requestId
    ): array {
        try {
            $tron = $this->tronClient($privateKey);

            if (!$tron->isAddress($fromAddress)) {
                throw new \RuntimeException('Invalid source TRON address: ' . $fromAddress);
            }
            if (!$tron->isAddress($toAddress)) {
                throw new \RuntimeException('Invalid destination TRON address: ' . $toAddress);
            }

            $contract = $tron->contract($this->usdtContract);
            $contract->setFeeLimit($this->feeLimitTrx);

            // TRC20Contract::transfer() expects a human-readable amount and
            // scales it internally using the token's on-chain decimals (6 for USDT).
            $response = $contract->transfer($toAddress, (string) $amount, $fromAddress);

            $txid = $response['txID'] ?? $response['txid'] ?? null;
            if (!$txid) {
                throw new \RuntimeException('Broadcast did not return a transaction ID: ' . json_encode($response));
            }

            return [
                'ok'        => true,
                'txid'      => $txid,
                'requestId' => $requestId,
                'from'      => $fromAddress,
                'to'        => $toAddress,
                'amount'    => $amount,
                'contract'  => $this->usdtContract,
            ];
        } catch (TronException $e) {
            throw new \RuntimeException('TRON transfer failed: ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new \RuntimeException('USDT send failed: ' . $e->getMessage());
        }
    }

}
