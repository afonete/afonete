<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FomLicenceMiner extends Model
{
    use HasFactory;

    protected $table = 'fom_licence_miners';

    /**
     * Weekly Volume Bonus Cap per package (spec §69) — the maximum VB a
     * user can earn as affiliate bonus per week. Seeded once into the
     * weekly_vb_cap column; admin-editable afterwards. Direct referral
     * bonus (L1 Direct Sponsors) is NOT capped — only the volume bonus.
     */
    public const WEEKLY_VB_CAPS = [
        'BASIC'      => 200,
        'STARTER'    => 1000,
        'LIGHT'      => 2000,
        'PRO'        => 3000,
        'ADVANCED'   => 5000,
        'PREMIUM'    => 12000,
        'TYCOON'     => 15000,
        'MASTER'     => 30000,
        'PRO MASTER' => 40000,
        'SUPER'      => 50000,
    ];

    /**
     * The weekly VB cap for a USER = the cap of their CURRENT FOM package
     * (the most recently activated, still-active one — user decision §70).
     * 0 = no active FOM package (no volume payout possible anyway).
     */
    public static function weeklyVbCapForUser(int $userId): float
    {
        try {
            self::ensureSchema();

            $current = \App\Models\Payment::where('user', (string) $userId)
                ->onlyFom()
                ->where('status', '1')
                ->where('is_expired', false)
                ->orderByDesc('created_at')
                ->first();

            if (!$current) {
                return 0.0;
            }

            $name = strtoupper(trim((string) ($current->category ?: $current->package)));
            $pkg = self::whereRaw('UPPER(name) = ?', [$name])->first();

            $cap = $pkg ? (float) ($pkg->weekly_vb_cap ?? 0) : 0.0;
            if ($cap <= 0) {
                $cap = (float) (self::WEEKLY_VB_CAPS[$name] ?? 0);
            }

            return $cap;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("weeklyVbCapForUser failed for #{$userId}: " . $e->getMessage());
            return 0.0;
        }
    }

    protected $fillable = [
        'name',
        'price',
        'display_price',
        'tokens',
        'token_symbol',
        'duration_days',
        'token_bonus',
        'direct_sponsors',
        'affiliate_vbonus',
        'space_shop_limit',
        'volume_point',
        'volume_bonus',
        'weekly_vb_cap',
        'education_access',
        'unlocked_per_week',
        'allowed_loan',
        'investment_option',
        'total_return',
        'is_active',
        'sort_order',
    ];

    /**
     * True when the table has all columns the current code writes.
     * Used by admin write paths to fail loudly (instead of a silent
     * QueryException → redirect) when ensureSchema() could not alter the
     * table, e.g. because the DB user lacks ALTER privileges.
     */
    public static function hasCurrentSchema(): bool
    {
        try {
            return Schema::hasTable('fom_licence_miners')
                && Schema::hasColumn('fom_licence_miners', 'token_symbol')
                && Schema::hasColumn('fom_licence_miners', 'volume_bonus')
                && Schema::hasColumn('fom_licence_miners', 'education_access');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Authoritative check: is this activation code a FOM Licence Miner code?
     *
     * Rules (UVP always wins on ambiguity so the two systems stay separate):
     *  - 'FOM-' prefixed codes are FOM (generated only by buyFomPackage).
     *  - Leader / UVP / FC marker packages are never FOM.
     *  - A package name that exists in Adventures (UVP) is treated as UVP
     *    even if a FOM package shares the same name — UVP codes must never
     *    be swallowed by the FOM activation path.
     *  - Otherwise, FOM if the package name exists in fom_licence_miners.
     */
    public static function isFomActivation($activation): bool
    {
        if (!$activation) {
            return false;
        }

        $code = strtoupper(trim((string) ($activation->code ?? '')));
        if (str_starts_with($code, 'FOM-')) {
            return true;
        }

        $pkg = strtoupper(trim((string) ($activation->package ?? '')));
        if ($pkg === '') {
            return false;
        }

        // Never FOM: leader codes and explicit UVP/FC markers.
        if (in_array($pkg, ['TEAM_LEADER', 'SUPER_LEADER', 'TM', 'VENTURE', 'UVP', 'FC'], true)) {
            return false;
        }

        // UVP wins on name collision.
        try {
            if (\App\Models\Adventures::whereRaw('UPPER(name) = ?', [$pkg])->exists()) {
                return false;
            }
        } catch (\Throwable $e) {
            // If Adventures can't be checked, fall through to the FOM check.
        }

        try {
            return self::whereRaw('UPPER(name) = ?', [$pkg])->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Safely extracts a clean float numeric value from any string, number, or null.
     */
    public static function cleanNum($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        if (is_string($value) && trim($value) !== '') {
            $clean = preg_replace('/[^0-9.]/', '', str_replace(',', '', $value));
            if (is_numeric($clean) && $clean !== '') {
                return (float) $clean;
            }
        }
        return 0.0;
    }

    /**
     * Accessor for formatted tokens string e.g. "20,833,333"
     */
    public function getFormattedTokensAttribute(): string
    {
        return number_format(self::cleanNum($this->tokens));
    }

    /**
     * Accessor for formatted price e.g. "25,000.00"
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format(self::cleanNum($this->price), 2);
    }

    /**
     * Accessor for formatted total return string e.g. "41,666,666"
     */
    public function getFormattedTotalReturnAttribute(): string
    {
        return number_format(self::cleanNum($this->total_return));
    }

    /**
     * Accessor for volume point or volume bonus text.
     *
     * Legacy accessor kept for backward compatibility: it previously guessed
     * "bonus vs point" from the magnitude of volume_point. Now that the two
     * are separate columns it shows Volume Point, falling back to the old
     * heuristic only for un-migrated rows.
     */
    public function getFormattedVolumePointAttribute(): string
    {
        $point = self::cleanNum($this->volume_point);
        $bonus = self::cleanNum($this->volume_bonus ?? 0);

        if ($bonus > 0) {
            return "Volume Point: " . (int)$point . " Point";
        }

        // Legacy row (volume_bonus not yet set): preserve old display logic.
        if ($point > 10) {
            return "Volume Bonus: " . number_format($point);
        }
        return "Volume Point: " . (int)$point . " Point";
    }

    /**
     * The token symbol shown for this package: per-package override if the
     * admin configured one, otherwise the global TokenSetting symbol.
     */
    public function effectiveTokenSymbol(): string
    {
        $own = trim((string) ($this->token_symbol ?? ''));
        if ($own !== '') {
            return $own;
        }
        try {
            return \App\Models\TokenSetting::currentSymbol();
        } catch (\Throwable $e) {
            return 'FOCOIN';
        }
    }

    /**
     * Resolve the effective token symbol for a package NAME (used by views
     * that only carry the package name, e.g. activation-code tables and the
     * escrow installment list). Falls back to the global symbol when the
     * package doesn't exist or has no override. Cached per request.
     */
    public static function symbolForPackageName($name): string
    {
        static $cache = [];

        $key = strtoupper(trim((string) $name));

        if ($key !== '' && array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        $global = 'FOCOIN';
        try {
            $global = \App\Models\TokenSetting::currentSymbol();
        } catch (\Throwable $e) {
        }

        if ($key === '') {
            return $global;
        }

        try {
            $pkg = self::whereRaw('UPPER(name) = ?', [$key])->first();
            $cache[$key] = $pkg ? $pkg->effectiveTokenSymbol() : $global;
        } catch (\Throwable $e) {
            $cache[$key] = $global;
        }

        return $cache[$key];
    }

    /**
     * Per-request memo so the schema/seed check runs at most once per request
     * instead of on every call (it used to run Schema::hasTable + a full table
     * scan/rewrite on every home-page and admin-index hit).
     */
    protected static $ensured = false;

    /**
     * Ensures table exists and seeds/sanitizes initial packages if empty or outdated.
     */
    public static function ensureTableAndData()
    {
        if (static::$ensured) {
            return;
        }
        static::$ensured = true;

        self::ensureSchema();

        try {
            if (self::count() === 0) {
                self::seedDefaults();
            } else {
                self::sanitizeExistingRecords();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("FomLicenceMiner ensureTableAndData error: " . $e->getMessage());
        }
    }

    /** Per-request memo for the schema check. */
    protected static $schemaEnsured = false;

    /**
     * Ensures the table exists AND has all current columns (self-heal for
     * installs that don't run migrations). Called by every admin write path
     * (create/store/edit/update) so saving token_symbol / volume_bonus /
     * education_access can never fail on a pre-upgrade database.
     */
    public static function ensureSchema()
    {
        if (static::$schemaEnsured) {
            return;
        }
        static::$schemaEnsured = true;

        try {
            if (!Schema::hasTable('fom_licence_miners')) {
                Schema::create('fom_licence_miners', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->decimal('price', 12, 2)->default(0.00);
                    $table->string('display_price')->nullable();
                    $table->decimal('tokens', 20, 0)->default(0);
                    $table->integer('duration_days')->default(600);
                    $table->decimal('token_bonus', 8, 2)->default(0.00);
                    $table->decimal('direct_sponsors', 8, 2)->default(0.00);
                    $table->decimal('affiliate_vbonus', 8, 2)->default(10.00);
                    $table->string('space_shop_limit')->nullable()->default('Space Shop Room Limit');
                    $table->decimal('volume_point', 12, 0)->default(0);
                    $table->decimal('volume_bonus', 20, 0)->default(0);
                    $table->string('token_symbol', 50)->nullable();
                    $table->string('education_access')->nullable()->default('Access to Education Courses');
                    $table->string('unlocked_per_week')->default('YES');
                    $table->string('allowed_loan')->nullable();
                    $table->string('investment_option')->nullable();
                    $table->decimal('total_return', 20, 0)->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                });
            }

            // Self-heal existing installs: add the newer columns when missing.
            if (Schema::hasTable('fom_licence_miners')) {
                if (!Schema::hasColumn('fom_licence_miners', 'volume_bonus')) {
                    Schema::table('fom_licence_miners', function (Blueprint $table) {
                        $table->decimal('volume_bonus', 20, 0)->default(0);
                    });
                    // Migrate legacy data: the old convention stored a "bonus"
                    // in volume_point when its value was > 10. Mirror the
                    // migration exactly: move the value AND reset the point.
                    foreach (self::where('volume_point', '>', 10)->get() as $legacy) {
                        $legacy->volume_bonus = self::cleanNum($legacy->volume_point);
                        $legacy->volume_point = 0;
                        $legacy->save();
                    }
                }
                if (!Schema::hasColumn('fom_licence_miners', 'token_symbol')) {
                    Schema::table('fom_licence_miners', function (Blueprint $table) {
                        $table->string('token_symbol', 50)->nullable();
                    });
                }
                if (!Schema::hasColumn('fom_licence_miners', 'weekly_vb_cap')) {
                    Schema::table('fom_licence_miners', function (Blueprint $table) {
                        $table->decimal('weekly_vb_cap', 20, 2)->default(0);
                    });
                    // Seed the spec caps onto existing packages (0 = uncapped).
                    foreach (self::WEEKLY_VB_CAPS as $pkgName => $cap) {
                        self::whereRaw('UPPER(name) = ?', [$pkgName])->update(['weekly_vb_cap' => $cap]);
                    }
                }
                if (!Schema::hasColumn('fom_licence_miners', 'education_access')) {
                    Schema::table('fom_licence_miners', function (Blueprint $table) {
                        $table->string('education_access')->nullable()->default('Access to Education Courses');
                    });
                    self::query()->update(['education_access' => 'Access to Education Courses']);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("FomLicenceMiner ensureSchema error: " . $e->getMessage());
        }
    }

    /**
     * Sanitizes any existing legacy database records to convert string values to pure numeric floats.
     */
    public static function sanitizeExistingRecords()
    {
        try {
            $records = self::all();
            foreach ($records as $record) {
                $changed = false;
                
                $numTokens = self::cleanNum($record->tokens);
                if ((string)$record->tokens !== (string)$numTokens && $numTokens > 0) {
                    $record->tokens = $numTokens;
                    $changed = true;
                }

                $numPrice = self::cleanNum($record->price);
                if ((string)$record->price !== (string)$numPrice && $numPrice > 0) {
                    $record->price = $numPrice;
                    $changed = true;
                }

                $numTotalReturn = self::cleanNum($record->total_return);
                if ((string)$record->total_return !== (string)$numTotalReturn && $numTotalReturn > 0) {
                    $record->total_return = $numTotalReturn;
                    $changed = true;
                }

                $numBonus = self::cleanNum($record->token_bonus);
                if ((string)$record->token_bonus !== (string)$numBonus) {
                    $record->token_bonus = $numBonus;
                    $changed = true;
                }

                $numSponsors = self::cleanNum($record->direct_sponsors);
                if ((string)$record->direct_sponsors !== (string)$numSponsors) {
                    $record->direct_sponsors = $numSponsors;
                    $changed = true;
                }

                $numAffiliate = self::cleanNum($record->affiliate_vbonus);
                if ((string)$record->affiliate_vbonus !== (string)$numAffiliate) {
                    $record->affiliate_vbonus = $numAffiliate;
                    $changed = true;
                }

                $numVol = self::cleanNum($record->volume_point);
                if ((string)$record->volume_point !== (string)$numVol) {
                    $record->volume_point = $numVol;
                    $changed = true;
                }

                if ($changed) {
                    $record->save();
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("FomLicenceMiner sanitizeExistingRecords error: " . $e->getMessage());
        }
    }

    public static function seedDefaults()
    {
        $defaults = [
            [
                'name' => 'BASIC',
                'price' => 20.00,
                'display_price' => '20 USDT',
                'tokens' => 20000,
                'duration_days' => 600,
                'token_bonus' => 30.00,
                'direct_sponsors' => 5.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 4,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Not Allowed Loan',
                'investment_option' => 'No Access Investment Option',
                'total_return' => 26000,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'STARTER',
                'price' => 100.00,
                'display_price' => '100 USDT',
                'tokens' => 83333,
                'duration_days' => 600,
                'token_bonus' => 30.00,
                'direct_sponsors' => 8.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 20,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Allowed Loan',
                'investment_option' => 'Access Investment feature',
                'total_return' => 108333,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'LIGHT',
                'price' => 300.00,
                'display_price' => '300 USDT',
                'tokens' => 250000,
                'duration_days' => 600,
                'token_bonus' => 40.00,
                'direct_sponsors' => 5.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 4,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Not Allowed Loan',
                'investment_option' => 'No Access Investment Option',
                'total_return' => 26000,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'PRO',
                'price' => 500.00,
                'display_price' => '500 USDT',
                'tokens' => 250000,
                'duration_days' => 600,
                'token_bonus' => 45.00,
                'direct_sponsors' => 10.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 4,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Allowed Loan',
                'investment_option' => 'Access Investment Option',
                'total_return' => 604116,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'ADVANCED',
                'price' => 1000.00,
                'display_price' => '1000 USDT',
                'tokens' => 833333,
                'duration_days' => 600,
                'token_bonus' => 50.00,
                'direct_sponsors' => 11.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 200,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Allowed Loan',
                'investment_option' => 'Investment Option',
                'total_return' => 1250000,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'PREMIUM',
                'price' => 3000.00,
                'display_price' => '3000 USDT',
                'tokens' => 2500000,
                'duration_days' => 600,
                'token_bonus' => 60.00,
                'direct_sponsors' => 12.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 200,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Allowed Loan',
                'investment_option' => 'Investment Option',
                'total_return' => 4000000,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'TYCOON',
                'price' => 5000.00,
                'display_price' => '5000 USDT',
                'tokens' => 4166667,
                'duration_days' => 600,
                'token_bonus' => 70.00,
                'direct_sponsors' => 14.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 1000,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Allowed Loan',
                'investment_option' => 'Access Investment feature',
                'total_return' => 7083333,
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'MASTER',
                'price' => 10000.00,
                'display_price' => '10,000 USDT',
                'tokens' => 20000,
                'duration_days' => 600,
                'token_bonus' => 30.00,
                'direct_sponsors' => 5.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limite',
                'volume_point' => 4,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Not Allowed Loan',
                'investment_option' => 'No Access Investment Option',
                'total_return' => 26000,
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'PRO MASTER',
                'price' => 20000.00,
                'display_price' => '20,000 USDT',
                'tokens' => 16666667,
                'duration_days' => 600,
                'token_bonus' => 90.00,
                'direct_sponsors' => 18.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 4000,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Allowed Loan',
                'investment_option' => 'Access Investment feature',
                'total_return' => 31666666,
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'SUPER',
                'price' => 25000.00,
                'display_price' => '25K - 200K USDT',
                'tokens' => 20833333,
                'duration_days' => 600,
                'token_bonus' => 100.00,
                'direct_sponsors' => 20.00,
                'affiliate_vbonus' => 10.00,
                'space_shop_limit' => 'Space Shop Room Limit',
                'volume_point' => 6000,
                'unlocked_per_week' => 'YES',
                'allowed_loan' => 'Allowed Loan',
                'investment_option' => 'Access Investment feature',
                'total_return' => 41666666,
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($defaults as $data) {
            // Weekly Volume Bonus Cap (spec §69) from the canonical table.
            $data['weekly_vb_cap'] = self::WEEKLY_VB_CAPS[strtoupper(trim($data['name']))] ?? 0;
            // Split legacy volume_point into the two distinct fields:
            // values > 10 were "Volume Bonus", small values are "Volume Point".
            $vol = self::cleanNum($data['volume_point'] ?? 0);
            if ($vol > 10) {
                $data['volume_bonus'] = $vol;
                $data['volume_point'] = 0;
            } else {
                $data['volume_bonus'] = 0;
            }

            // Every FOM package includes access to education courses.
            $data['education_access'] = $data['education_access'] ?? 'Access to Education Courses';

            // token_symbol left null → falls back to global TokenSetting symbol.
            self::create($data);
        }
    }
}
