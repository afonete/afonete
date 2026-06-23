<?php

namespace App\Services;

use App\Models\BlockchainAuditLog;
use App\Models\BlockchainDepositAddress;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Direct TRON Blockchain Integration (TRC20 USDT).
 *
 * TronGrid is used for chain reads / transaction monitoring.
 * A separate local signer service is used for key generation and withdrawals,
 * so PHP never tries to implement fragile ECDSA/TRON signing itself.
 */
class TronBlockchainService
{
    protected ?string $apiKey;
    protected string $baseUrl;
    protected string $usdtContract;
    protected ?string $hotWalletAddress;
    protected ?string $signerUrl;
    protected ?string $signerSecret;
    protected int $signerTimeout;

    public function __construct()
    {
        $this->apiKey           = config('services.tron.api_key') ?: env('TRONGRID_API_KEY');
        $this->baseUrl          = rtrim(config('services.tron.base_url') ?: env('TRON_FULL_HOST', 'https://api.trongrid.io'), '/');
        $this->usdtContract     = config('services.tron.usdt_contract') ?: env('TRON_USDT_CONTRACT', 'TKu83PAfPbGd6KmoUx8dmST2qpdb2nnV8w');
        $this->hotWalletAddress = config('services.tron.hot_wallet') ?: env('TRON_HOT_WALLET_ADDRESS');
        $this->signerUrl        = rtrim(config('services.tron.signer_url') ?: env('TRON_SIGNER_URL', 'http://127.0.0.1:8787'), '/');
        $this->signerSecret     = config('services.tron.signer_secret') ?: env('TRON_SIGNER_SECRET');
        $this->signerTimeout    = (int) (config('services.tron.signer_timeout') ?: env('TRON_SIGNER_TIMEOUT', 20));
    }

    public function getHotWalletAddress(): ?string
    {
        return $this->hotWalletAddress;
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

    /** Ask the local signer service to generate a TRON keypair. */
    public function generateAddress(): array
    {
        return $this->signerPost('/address/generate', [
            'requestId' => (string) Str::uuid(),
        ]);
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

    /** Send USDT TRC20 from the configured hot wallet via the signer service. */
    public function sendUsdt(string $toAddress, float $amount, ?string $requestId = null): array
    {
        if (!$this->hotWalletAddress) {
            throw new \RuntimeException('TRON_HOT_WALLET_ADDRESS is not configured.');
        }

        $requestId = $requestId ?: (string) Str::uuid();

        $result = $this->signerPost('/usdt/send', [
            'to'        => $toAddress,
            'amount'    => $amount,
            'requestId' => $requestId,
        ]);

        if (empty($result['txid']) && empty($result['txHash'])) {
            throw new \RuntimeException($result['message'] ?? 'Signer did not return a transaction hash.');
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

    protected function signerPost(string $path, array $payload): array
    {
        if (!$this->signerUrl) {
            throw new \RuntimeException('TRON_SIGNER_URL is not configured.');
        }
        if (!$this->signerSecret) {
            throw new \RuntimeException('TRON_SIGNER_SECRET is not configured.');
        }

        $response = Http::withHeaders([
            'X-Signer-Secret' => $this->signerSecret,
            'Accept'          => 'application/json',
        ])->timeout($this->signerTimeout)->post($this->signerUrl . $path, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('Signer error HTTP ' . $response->status() . ': ' . $response->body());
        }

        $json = $response->json();
        if (!is_array($json)) {
            throw new \RuntimeException('Signer returned invalid JSON.');
        }
        if (($json['ok'] ?? true) === false) {
            throw new \RuntimeException($json['message'] ?? 'Signer returned ok=false.');
        }

        return $json;
    }
}
