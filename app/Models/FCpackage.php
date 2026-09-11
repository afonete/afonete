<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;

class FCpackage extends Model
{
    use HasFactory;

    protected $table = "fcspackages";
    protected $fillable = [
        "name",
        "price",
        "default_token",
        "token_price",  // Admin-set price per token
        "loan_min",
        "loan_max",
        "ads_credits",
        "free_shop_room",
        // NOTE: FC VIP packages are LIFETIME — no duration column, no expiration.
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'default_token'  => 'integer',
        'token_price'    => 'decimal:4',
        'loan_min'       => 'decimal:2',
        'loan_max'       => 'decimal:2',
        'ads_credits'    => 'integer',
        'free_shop_room' => 'boolean',
    ];

    public function investments()
    {
        return $this->hasMany(Payment::class,"package","id");
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable', 'payable_type', 'payable_id');
    }

    /**
     * FC-card feature lines use defaults when admin leaves a field blank.
     * "DMaster coin card" is the product name and does not need its own column.
     */
    public function loanRangeLabel(): string
    {
        $min = $this->loan_min;
        $max = $this->loan_max;
        if ($min !== null && $max !== null) {
            return '$' . number_format((float) $min, 0) . '–$' . number_format((float) $max, 0);
        }
        if ($min !== null) {
            return 'from $' . number_format((float) $min, 0);
        }
        if ($max !== null) {
            return 'up to $' . number_format((float) $max, 0);
        }
        return '$1,000–$50,000';
    }

    public function adsCreditsLabel(): string
    {
        $c = $this->ads_credits;
        return $c !== null ? number_format((int) $c) . ' ads' : '100,000 ads';
    }

    public function tokensLabel(): string
    {
        $t = (int) $this->default_token;
        if ($t <= 0) {
            return '0';
        }
        return number_format($t);
    }
}
