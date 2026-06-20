<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Extend deposit_wallets to support:
 *   1. Crypto (USDT/BTC/ETH/BNB) — one row per crypto × network (TRC-20/ERC-20/BEP-20)
 *   2. Advcash (USD / EUR)        — one row per currency, "network" = "ADVCASH"
 *   3. Perfect Money (USD / EUR)  — one row per currency, "network" = "PERFECT_MONEY"
 *
 * The previous schema forced `network` to be unique, which only worked
 * for the 3 seeded USDT networks. Now we relax the unique constraint
 * and key uniqueness off (type, currency, network).
 *
 * Real correspondence (per the spec):
 *   USDT  → TRC-20 (Tron, "T..." addresses) + ERC-20 + BEP-20 + Polygon
 *   BTC   → Bitcoin mainnet ("1/3/bc1..." addresses)
 *   ETH   → ERC-20 (Ethereum mainnet, "0x..." addresses)
 *   BNB   → BEP-20 (BSC, "0x..." addresses)
 *   Advcash       → account number ("U 8678 2735 0323")
 *   Perfect Money → account number ("U16443155")
 *
 * NOTE: This migration uses RAW SQL (`DB::statement`) for column
 * modifications instead of Schema Builder's `->change()` method.
 * That sidesteps the `doctrine/dbal` version-compatibility issue
 * (Laravel 8 needs ^2.6 || ^3.0; some installs have 4.x which
 * is incompatible).
 */
class ExtendDepositWalletsForCryptoAndProcessors extends Migration
{
    public function up()
    {
        $driver = DB::connection()->getDriverName();

        // ── Add new columns (one-by-one with raw SQL for portability) ──

        // `type` discriminator
        if (!Schema::hasColumn('deposit_wallets', 'type')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `deposit_wallets`
                    ADD COLUMN `type` VARCHAR(20) NOT NULL DEFAULT 'crypto'
                    COMMENT 'crypto | advcash | perfect_money' AFTER `id`");
            } else {
                // sqlite / pgsql — column add is simpler
                DB::statement("ALTER TABLE deposit_wallets ADD COLUMN type VARCHAR(20) NOT NULL DEFAULT 'crypto'");
            }
        }

        // Drop the old UNIQUE index on `network` so multiple non-crypto rows
        // can share a network value (e.g. Advcash rows all have network='ADVCASH').
        try {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `deposit_wallets` DROP INDEX `deposit_wallets_network_unique`");
            } elseif ($driver === 'sqlite') {
                DB::statement("DROP INDEX IF EXISTS deposit_wallets_network_unique");
            } elseif ($driver === 'pgsql') {
                DB::statement("ALTER TABLE deposit_wallets DROP CONSTRAINT IF EXISTS deposit_wallets_network_unique");
            }
        } catch (\Throwable $e) {
            // Index may not exist on this DB — ignore.
        }

        // Make `network` nullable so payment-processor rows can omit it.
        // Using raw ALTER (no ->change() → no doctrine/dbal needed).
        if (Schema::hasColumn('deposit_wallets', 'network')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `deposit_wallets` MODIFY COLUMN `network` VARCHAR(30) NULL");
            } elseif ($driver === 'sqlite') {
                // SQLite doesn't support MODIFY; recreating the column is heavy.
                // We rely on the seed code to insert NULLs directly.
                // (no-op)
            } elseif ($driver === 'pgsql') {
                DB::statement("ALTER TABLE deposit_wallets ALTER COLUMN network DROP NOT NULL");
            }
        }

        // `display_order` (admin reordering)
        if (!Schema::hasColumn('deposit_wallets', 'display_order')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `deposit_wallets`
                    ADD COLUMN `display_order` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `is_active`");
            } else {
                DB::statement("ALTER TABLE deposit_wallets
                    ADD COLUMN display_order INTEGER NOT NULL DEFAULT 0");
            }
        }

        // `instructions` (free-text shown to the user on Advcash / Perfect Money tabs)
        if (!Schema::hasColumn('deposit_wallets', 'instructions')) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE `deposit_wallets`
                    ADD COLUMN `instructions` TEXT NULL AFTER `notes`");
            } else {
                DB::statement("ALTER TABLE deposit_wallets ADD COLUMN instructions TEXT NULL");
            }
        }

        // Re-add a more useful composite unique key (type, currency, network)
        // Using raw SQL so it works on both MySQL and SQLite without doctrine.
        try {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement(
                    "CREATE UNIQUE INDEX `deposit_wallets_type_currency_network_unique`
                     ON `deposit_wallets` (`type`, `currency`, `network`)"
                );
            } elseif ($driver === 'sqlite') {
                DB::statement(
                    "CREATE UNIQUE INDEX IF NOT EXISTS deposit_wallets_type_currency_network_unique
                     ON deposit_wallets (type, currency, network)"
                );
            } elseif ($driver === 'pgsql') {
                DB::statement(
                    "CREATE UNIQUE INDEX IF NOT EXISTS deposit_wallets_type_currency_network_unique
                     ON deposit_wallets (type, currency, network)"
                );
            }
        } catch (\Throwable $e) {
            // Index may already exist — ignore.
        }

        // ── Seed the new deposit options ──
        $now = now();

        $rows = [
            // ── Crypto: USDT ──
            ['type' => 'crypto', 'currency' => 'USDT', 'network' => 'TRC-20',  'label' => 'USDT (Tron / TRC-20) — Recommended', 'wallet_address' => env('DEPOSIT_USDT_TRC20', 'TYourCompanyTronAddressHere'), 'display_order' => 10],
            ['type' => 'crypto', 'currency' => 'USDT', 'network' => 'ERC-20',  'label' => 'USDT (Ethereum / ERC-20)',                  'wallet_address' => env('DEPOSIT_USDT_ERC20', '0xYourCompanyEthAddressHere'),  'display_order' => 11],
            ['type' => 'crypto', 'currency' => 'USDT', 'network' => 'BEP-20',  'label' => 'USDT (BSC / BEP-20)',                       'wallet_address' => env('DEPOSIT_USDT_BEP20', '0xYourCompanyBscAddressHere'),  'display_order' => 12],
            ['type' => 'crypto', 'currency' => 'USDT', 'network' => 'POLYGON', 'label' => 'USDT (Polygon)',                              'wallet_address' => env('DEPOSIT_USDT_POLYGON', '0xYourCompanyPolygonAddressHere'), 'display_order' => 13],

            // ── Crypto: BTC ──
            ['type' => 'crypto', 'currency' => 'BTC',  'network' => 'BITCOIN', 'label' => 'Bitcoin (BTC) mainnet',  'wallet_address' => env('DEPOSIT_BTC', '15xxmHCBKacT8PJJxoJNK4wNZf7tcns9jA'), 'display_order' => 20],

            // ── Crypto: ETH ──
            ['type' => 'crypto', 'currency' => 'ETH',  'network' => 'ERC-20',  'label' => 'Ethereum (ETH / ERC-20)', 'wallet_address' => env('DEPOSIT_ETH', '0xYourCompanyEthAddressHere'), 'display_order' => 30],

            // ── Crypto: BNB ──
            ['type' => 'crypto', 'currency' => 'BNB',  'network' => 'BEP-20',  'label' => 'BNB (BSC / BEP-20)',     'wallet_address' => env('DEPOSIT_BNB', '0xYourCompanyBnbAddressHere'),  'display_order' => 40],

            // ── Advcash ──
            ['type' => 'advcash', 'currency' => 'USD', 'network' => null,  'label' => 'Advcash USD',  'wallet_address' => env('DEPOSIT_ADVCASH_USD', 'U 8678 2735 0323'), 'display_order' => 50, 'instructions' => "1) Log in to your Advcash account.\n2) Go to \"Send Funds\".\n3) Choose wallet Balance, enter amount.\n4) Enter the Advcash account number: U 8678 2735 0323\n5) Review and click \"Preview\" / \"Continue\".\n6) Authorize by clicking \"Send\" / \"Confirm\".\n7) Once you receive a confirmation, return here and submit the deposit request."],
            ['type' => 'advcash', 'currency' => 'EUR', 'network' => null,  'label' => 'Advcash EUR (coming soon)', 'wallet_address' => env('DEPOSIT_ADVCASH_EUR', ''), 'display_order' => 51, 'is_active' => false, 'instructions' => 'EUR account coming soon. Please use USD for now.'],

            // ── Perfect Money ──
            ['type' => 'perfect_money', 'currency' => 'USD', 'network' => null, 'label' => 'Perfect Money USD',  'wallet_address' => env('DEPOSIT_PM_USD', 'U16443155'), 'display_order' => 60, 'instructions' => "1) Log in to your Perfect Money account.\n2) Navigate to the \"Transfer\" option in the main menu.\n3) Enter the account number: U16443155\n4) Enter the amount.\n5) Review and click \"Preview\" / \"Continue\".\n6) Enter security code / answer security question.\n7) Click \"Send\" / \"Confirm\".\n8) Return here and submit the deposit request."],
            ['type' => 'perfect_money', 'currency' => 'EUR', 'network' => null, 'label' => 'Perfect Money EUR (coming soon)', 'wallet_address' => env('DEPOSIT_PM_EUR', ''), 'display_order' => 61, 'is_active' => false, 'instructions' => 'EUR account coming soon. Please use USD for now.'],
        ];

        foreach ($rows as $r) {
            $exists = DB::table('deposit_wallets')
                ->where('type', $r['type'])
                ->where('currency', $r['currency'])
                ->where(function ($q) use ($r) {
                    if ($r['network'] === null) {
                        $q->whereNull('network');
                    } else {
                        $q->where('network', $r['network']);
                    }
                })
                ->exists();
            if ($exists) continue;
            DB::table('deposit_wallets')->insert(array_merge([
                'min_amount'     => 10,
                'max_amount'     => null,
                'is_active'      => true,
                'notes'          => null,
                'instructions'   => $r['instructions'] ?? null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ], $r));
        }
    }

    public function down()
    {
        $driver = DB::connection()->getDriverName();

        try {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("DROP INDEX `deposit_wallets_type_currency_network_unique` ON `deposit_wallets`");
            } else {
                DB::statement("DROP INDEX IF EXISTS deposit_wallets_type_currency_network_unique");
            }
        } catch (\Throwable $e) {}

        // Drop the columns we added. (Network back to NOT NULL is best-effort.)
        Schema::table('deposit_wallets', function (Blueprint $table) {
            if (Schema::hasColumn('deposit_wallets', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('deposit_wallets', 'display_order')) {
                $table->dropColumn('display_order');
            }
            if (Schema::hasColumn('deposit_wallets', 'instructions')) {
                $table->dropColumn('instructions');
            }
        });
    }
}
