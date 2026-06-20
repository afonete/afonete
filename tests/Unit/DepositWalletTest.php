<?php

namespace Tests\Unit\Models;

use App\Models\DepositWallet;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for DepositWallet model helpers — the QR code generation,
 * address-format validation, and type label helpers used by the deposit
 * page on the user side.
 *
 * These tests do NOT touch the database — they only exercise pure
 * helpers on a hand-built model instance.
 */
class DepositWalletTest extends TestCase
{
    // ── qrPayload() — crypto URI schemes ─────────────────────────────

    /** @test */
    public function tron_address_gets_tron_uri_prefix(): void
    {
        $w = $this->wallet(network: 'TRC-20', address: 'TXYZabc123def456ghi789jkl012mno345pqr');
        $this->assertStringStartsWith('tron:', $w->qrPayload());
        $this->assertStringContainsString('TXYZabc', $w->qrPayload());
    }

    /** @test */
    public function bnb_address_gets_bsc_uri_prefix(): void
    {
        $w = $this->wallet(network: 'BEP-20', address: '0xabc1234567890abcdef1234567890ABCDEF123456');
        $this->assertStringStartsWith('bsc:', $w->qrPayload());
        $this->assertStringContainsString('0xabc1234', $w->qrPayload());
    }

    /** @test */
    public function eth_address_gets_ethereum_uri_prefix(): void
    {
        $w = $this->wallet(network: 'ERC-20', address: '0xabc1234567890abcdef1234567890ABCDEF123456');
        $this->assertStringStartsWith('ethereum:', $w->qrPayload());
    }

    /** @test */
    public function bitcoin_address_gets_bitcoin_uri_prefix(): void
    {
        $w = $this->wallet(network: 'BITCOIN', address: '15xxmHCBKacT8PJJxoJNK4wNZf7tcns9jA');
        $this->assertStringStartsWith('bitcoin:', $w->qrPayload());
    }

    /** @test */
    public function advcash_account_does_not_get_a_uri_prefix(): void
    {
        // Advcash / Perfect Money are account numbers, not blockchain addrs.
        $w = $this->wallet(type: 'advcash', address: 'U 8678 2735 0323');
        $this->assertSame('U 8678 2735 0323', $w->qrPayload());
    }

    /** @test */
    public function already_formed_uri_is_left_alone(): void
    {
        $w = $this->wallet(network: 'TRC-20', address: 'tron:TXYZabc');
        $this->assertSame('tron:TXYZabc', $w->qrPayload());
    }

    // ── qrImageUrl() — public API URL ─────────────────────────────────

    /** @test */
    public function qr_image_url_uses_free_public_api(): void
    {
        $w = $this->wallet(network: 'BITCOIN', address: '15xxmHCBKacT8PJJxoJNK4wNZf7tcns9jA');
        $url = $w->qrImageUrl();
        $this->assertStringStartsWith('https://api.qrserver.com/', $url);
        $this->assertStringContainsString(urlencode('bitcoin:15xxmHCBKacT8PJJxoJNK4wNZf7tcns9jA'), $url);
    }

    /** @test */
    public function qr_image_url_supports_custom_size(): void
    {
        $w = $this->wallet(network: 'TRC-20', address: 'TXYZabc');
        $this->assertStringContainsString('size=300x300', $w->qrImageUrl(300));
    }

    // ── addressLooksValid() — cheap shape check ─────────────────────

    /** @test */
    public function tron_address_passes_format_check(): void
    {
        $w = $this->wallet(network: 'TRC-20', address: 'TXYZabc123def456ghi789jkl012mno345pqr');
        $this->assertTrue($w->addressLooksValid());
    }

    /** @test */
    public function bad_tron_address_fails_format_check(): void
    {
        $w = $this->wallet(network: 'TRC-20', address: '0xnopenope');
        $this->assertFalse($w->addressLooksValid());
    }

    /** @test */
    public function eth_address_passes_format_check(): void
    {
        $w = $this->wallet(network: 'ERC-20', address: '0xabc1234567890abcdef1234567890ABCDEF123456');
        $this->assertTrue($w->addressLooksValid());
    }

    /** @test */
    public function empty_address_fails_format_check(): void
    {
        $w = $this->wallet(network: 'TRC-20', address: '');
        $this->assertFalse($w->addressLooksValid());
    }

    /** @test */
    public function bitcoin_legacy_address_passes_format_check(): void
    {
        $w = $this->wallet(network: 'BITCOIN', address: '15xxmHCBKacT8PJJxoJNK4wNZf7tcns9jA');
        $this->assertTrue($w->addressLooksValid());
    }

    // ── kindLabel() ──────────────────────────────────────────────────

    /** @test */
    public function kind_label_for_crypto(): void
    {
        $w = $this->wallet(type: 'crypto');
        $this->assertSame('Crypto', $w->kindLabel());
    }

    /** @test */
    public function kind_label_for_advcash(): void
    {
        $w = $this->wallet(type: 'advcash');
        $this->assertSame('Advcash', $w->kindLabel());
    }

    /** @test */
    public function kind_label_for_perfect_money(): void
    {
        $w = $this->wallet(type: 'perfect_money');
        $this->assertSame('Perfect Money', $w->kindLabel());
    }

    // ── helpers ─────────────────────────────────────────────────────

    private function wallet(string $type = 'crypto', string $network = 'TRC-20', string $address = 'TXYZabc123def456ghi789jkl012mno345pqr'): DepositWallet
    {
        $w = new DepositWallet();
        $w->type = $type;
        $w->network = $network;
        $w->wallet_address = $address;
        $w->label = 'Test wallet';
        $w->currency = $type === 'crypto' ? 'USDT' : 'USD';
        $w->is_active = true;
        return $w;
    }
}
