<?php

namespace Tests\Unit\Models;

use App\Models\withdrawals;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for withdrawals model helpers — used by both the user
 * withdraw page and the admin approval queue.
 *
 * Covers:
 *   - methodLabel / typeLabel helpers
 *   - destinationLabel (multi-line address rendering)
 *   - addressLooksValid shape check for all crypto networks
 *   - status / method constants
 */
class WithdrawalsTest extends TestCase
{
    // ── Real address fixtures (correct length) ──
    private const TRC20_VALID = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'; // 34 chars
    private const ETH_VALID   = '0xdAC17F958D2ee523a2206206994597C13D831ec7'; // 42 chars
    private const BTC_VALID   = '15xxmHCBKacT8PJJxoJNK4wNZf7tcns9jA';       // 34 chars

    // ── methodLabel() ────────────────────────────────────────────────

    /** @test */
    public function method_label_for_crypto(): void
    {
        $w = $this->w(method: 'crypto');
        $this->assertSame('Crypto', $w->methodLabel());
    }

    /** @test */
    public function method_label_for_advcash(): void
    {
        $w = $this->w(method: 'advcash');
        $this->assertSame('Advcash', $w->methodLabel());
    }

    /** @test */
    public function method_label_for_perfect_money(): void
    {
        $w = $this->w(method: 'perfect_money');
        $this->assertSame('Perfect Money', $w->methodLabel());
    }

    /** @test */
    public function method_label_defaults_to_crypto_for_unknown(): void
    {
        $w = $this->w(method: 'somethingelse');
        $this->assertSame('Crypto', $w->methodLabel());
    }

    // ── typeLabel() ──────────────────────────────────────────────────

    /** @test */
    public function type_label_is_instant_when_plisio_txn_id_set(): void
    {
        $w = $this->w(method: 'crypto');
        $w->plisio_txn_id = 'pl_123';
        $this->assertSame('Instant', $w->typeLabel());
    }

    /** @test */
    public function type_label_is_manual_when_no_plisio_txn_id(): void
    {
        $w = $this->w(method: 'crypto');
        $this->assertNull($w->plisio_txn_id);
        $this->assertSame('Manual', $w->typeLabel());
    }

    // ── destinationLabel() ───────────────────────────────────────────

    /** @test */
    public function destination_label_for_crypto_includes_currency_network_and_address(): void
    {
        $w = $this->w(method: 'crypto', currency: 'USDT', network: 'TRC-20', address: self::TRC20_VALID);
        $this->assertSame('USDT (TRC-20) — ' . self::TRC20_VALID, $w->destinationLabel());
    }

    /** @test */
    public function destination_label_for_advcash_uses_method_label(): void
    {
        $w = $this->w(method: 'advcash', network: 'ADVCASH', currency: 'USD', address: 'U 8678 2735 0323');
        $this->assertSame('Advcash USD — U 8678 2735 0323', $w->destinationLabel());
    }

    /** @test */
    public function destination_label_for_perfect_money_uses_method_label(): void
    {
        $w = $this->w(method: 'perfect_money', network: 'PERFECT_MONEY', currency: 'USD', address: 'U16443155');
        $this->assertSame('Perfect Money USD — U16443155', $w->destinationLabel());
    }

    // ── addressLooksValid() ──────────────────────────────────────────

    /** @test */
    public function tron_address_passes_format_check(): void
    {
        $w = $this->w(network: 'TRC-20', address: self::TRC20_VALID);
        $this->assertTrue($w->addressLooksValid());
    }

    /** @test */
    public function bad_tron_address_fails_format_check(): void
    {
        $w = $this->w(network: 'TRC-20', address: '0xnopenope');
        $this->assertFalse($w->addressLooksValid());
    }

    /** @test */
    public function eth_address_passes_format_check(): void
    {
        $w = $this->w(network: 'ERC-20', address: self::ETH_VALID);
        $this->assertTrue($w->addressLooksValid());
    }

    /** @test */
    public function bnb_bep20_address_passes_format_check(): void
    {
        $w = $this->w(network: 'BEP-20', address: self::ETH_VALID);
        $this->assertTrue($w->addressLooksValid());
    }

    /** @test */
    public function bitcoin_legacy_address_passes_format_check(): void
    {
        $w = $this->w(network: 'BITCOIN', address: self::BTC_VALID);
        $this->assertTrue($w->addressLooksValid());
    }

    /** @test */
    public function empty_address_fails_format_check(): void
    {
        $w = $this->w(network: 'TRC-20', address: '');
        $this->assertFalse($w->addressLooksValid());
    }

    /** @test */
    public function advcash_account_passes_format_check(): void
    {
        // Advcash uses a free-text account number, not a blockchain address.
        // The generic check just requires >= 4 chars.
        $w = $this->w(method: 'advcash', network: 'ADVCASH', address: 'U 8678 2735 0323');
        $this->assertTrue($w->addressLooksValid());
    }

    /** @test */
    public function advcash_short_account_fails_format_check(): void
    {
        $w = $this->w(method: 'advcash', network: 'ADVCASH', address: 'U');
        $this->assertFalse($w->addressLooksValid());
    }

    // ── constants ───────────────────────────────────────────────────

    /** @test */
    public function method_constants_are_stable(): void
    {
        $this->assertSame('crypto',         withdrawals::METHOD_CRYPTO);
        $this->assertSame('advcash',        withdrawals::METHOD_ADVCASH);
        $this->assertSame('perfect_money',  withdrawals::METHOD_PERFECT_MONEY);
    }

    /** @test */
    public function status_constants_are_stable(): void
    {
        $this->assertSame('pending',     withdrawals::STATUS_PENDING);
        $this->assertSame('processing',  withdrawals::STATUS_PROCESSING);
        $this->assertSame('completed',   withdrawals::STATUS_COMPLETED);
        $this->assertSame('failed',      withdrawals::STATUS_FAILED);
    }

    // ── helpers ─────────────────────────────────────────────────────

    /**
     * Build a hand-crafted withdrawals instance. Defaults to crypto
     * USDT/TRC-20 with a valid 34-char Tron address.
     */
    private function w(
        string $method = 'crypto',
        string $network = 'TRC-20',
        string $currency = 'USDT',
        string $address = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'
    ): withdrawals {
        $w = new withdrawals();
        $w->method = $method;
        $w->network = $network;
        $w->currency = $currency;
        $w->wallet_address = $address;
        $w->amount = 100.00;
        $w->status = 'pending';
        return $w;
    }
}