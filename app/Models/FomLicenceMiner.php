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

    protected $fillable = [
        'name',
        'price',
        'display_price',
        'tokens',
        'duration_days',
        'token_bonus',
        'direct_sponsors',
        'affiliate_vbonus',
        'space_shop_limit',
        'volume_point',
        'unlocked_per_week',
        'allowed_loan',
        'investment_option',
        'total_return',
        'is_active',
        'sort_order',
    ];

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
     */
    public function getFormattedVolumePointAttribute(): string
    {
        $val = self::cleanNum($this->volume_point);
        if ($val > 10) {
            return "Volume Bonus: " . number_format($val);
        }
        return "Volume Point: " . (int)$val . " Point";
    }

    /**
     * Ensures table exists and seeds/sanitizes initial packages if empty or outdated.
     */
    public static function ensureTableAndData()
    {
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
                    $table->string('unlocked_per_week')->default('YES');
                    $table->string('allowed_loan')->nullable();
                    $table->string('investment_option')->nullable();
                    $table->decimal('total_return', 20, 0)->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                });
            }

            if (self::count() === 0) {
                self::seedDefaults();
            } else {
                self::sanitizeExistingRecords();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("FomLicenceMiner ensureTableAndData error: " . $e->getMessage());
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
            self::create($data);
        }
    }
}
