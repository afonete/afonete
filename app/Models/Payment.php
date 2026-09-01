<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Earnings;
use App\Models\User;
use App\Models\Adventures;

class Payment extends Model
{
    use HasFactory;

    protected $fillable =[
    'user',
    'package',
    'category',
    'category_id',
    'amount',
    'status',
    'paid',
    'over_paid',
    'expiration_date',
    'is_expired',
    'duration',
    'payable_id',
    'payable_type',
    ];

    /**
     * Cached (per-request) list of FOM Licence Miner package names, uppercased.
     * Used to recognise legacy FOM Payment rows that stored the package name
     * in `category` instead of the literal 'FOM'.
     */
    public static function fomPackageNames(): array
    {
        static $names = null;

        if ($names === null) {
            try {
                $names = \Illuminate\Support\Facades\Schema::hasTable('fom_licence_miners')
                    ? \App\Models\FomLicenceMiner::pluck('name')
                        ->map(function ($n) { return strtoupper(trim((string) $n)); })
                        ->filter()
                        ->values()
                        ->all()
                    : [];
            } catch (\Throwable $e) {
                $names = [];
            }
        }

        return $names;
    }

    /**
     * Is this payment a FOM Licence Miner package?
     *
     * All FOM Payment rows are written with `category` = the FOM package name
     * (BASIC, STARTER, ...) or the literal 'FOM' (see HomeController /
     * ActivationController FOM activation paths). Genuine UVP/FC investments
     * always carry category 'VENTURE' or 'FC', so those can never be
     * misclassified even if a package NAME happened to collide.
     *
     * FOM payments are token-mining licences released via escrow
     * installments — they are NOT UVP/FC investments and must never appear
     * in UVP listings or be renewed with Trading Vouchers.
     */
    public function isFom(): bool
    {
        $category = strtoupper(trim((string) $this->category));

        // Real UVP / FC investments are never FOM, regardless of name.
        if (in_array($category, ['VENTURE', 'FC'], true)) {
            return false;
        }

        if ($category === 'FOM') {
            return true;
        }

        $names = self::fomPackageNames();
        if (empty($names)) {
            return false;
        }

        return in_array($category, $names, true)
            || in_array(strtoupper(trim((string) $this->package)), $names, true);
    }

    /**
     * Leader "package" names stored in payments.category / payments.package
     * when a Team Leader / Super Leader activation code is redeemed
     * (ActivationController creates a Payment row for the leader code).
     * §84: those rows are NOT UVP investments — they must never feed the
     * UVP token fallback, daily-ROI loops or investment listings. Leader
     * tokens come EXCLUSIVELY from the admin-assigned activations.token.
     */
    public const LEADER_CATEGORIES = ['TEAM_LEADER', 'SUPER_LEADER', 'TM'];

    /** True when this payment row is a leader-code activation record. */
    public function isLeaderPayment(): bool
    {
        return in_array(strtoupper(trim((string) $this->category)), self::LEADER_CATEGORIES, true)
            || in_array(strtoupper(trim((string) $this->package)),  self::LEADER_CATEGORIES, true);
    }

    /**
     * Query scope: exclude Team Leader / Super Leader activation rows.
     * Chain with excludeFom() wherever "UVP/FC investments" are meant:
     *   Payment::where('user', $id)->excludeFom()->excludeLeader()->...
     */
    public function scopeExcludeLeader($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($c) {
                $c->whereNull('category')
                  ->orWhereNotIn(\Illuminate\Support\Facades\DB::raw('UPPER(category)'), self::LEADER_CATEGORIES);
            })->where(function ($p) {
                $p->whereNull('package')
                  ->orWhereNotIn(\Illuminate\Support\Facades\DB::raw('UPPER(package)'), self::LEADER_CATEGORIES);
            });
        });
    }

    /**
     * Query scope: exclude FOM Licence Miner payments (UVP/FC listings only).
     *
     * Rows with category VENTURE/FC are always kept. Everything else is
     * dropped when its category or package matches 'FOM' / a FOM package name.
     *
     * Usage: Payment::where('user', $id)->excludeFom()->...
     */
    public function scopeExcludeFom($query)
    {
        $names = array_merge(['FOM'], self::fomPackageNames());

        return $query->where(function ($q) use ($names) {
            // Keep genuine investments outright…
            $q->whereIn(\Illuminate\Support\Facades\DB::raw('UPPER(category)'), ['VENTURE', 'FC'])
              // …or anything that matches neither a FOM category nor a FOM package name.
              ->orWhere(function ($qq) use ($names) {
                  $qq->where(function ($c) use ($names) {
                      $c->whereNull('category')
                        ->orWhereNotIn(\Illuminate\Support\Facades\DB::raw('UPPER(category)'), $names);
                  })->where(function ($p) use ($names) {
                      $p->whereNull('package')
                        ->orWhereNotIn(\Illuminate\Support\Facades\DB::raw('UPPER(package)'), $names);
                  });
              });
        });
    }

    /**
     * Query scope: ONLY FOM Licence Miner payments — exact inverse of
     * scopeExcludeFom(). Rows with category VENTURE/FC are never FOM
     * (UVP wins name collisions); everything else is FOM when its category
     * or package matches 'FOM' / a FOM package name.
     *
     * Usage: Payment::where('user', $id)->onlyFom()->...
     */
    public function scopeOnlyFom($query)
    {
        $names = array_merge(['FOM'], self::fomPackageNames());

        return $query
            ->where(function ($q) {
                $q->whereNull('category')
                  ->orWhereNotIn(\Illuminate\Support\Facades\DB::raw('UPPER(category)'), ['VENTURE', 'FC']);
            })
            ->where(function ($q) use ($names) {
                $q->whereIn(\Illuminate\Support\Facades\DB::raw('UPPER(category)'), $names)
                  ->orWhereIn(\Illuminate\Support\Facades\DB::raw('UPPER(package)'), $names);
            });
    }

public function VenturePayable()
{
    return $this->morphTo();
}


public function user()
{
    return $this->belongsTo(User::class,"user","id");
}
public function package(){
    return $this->belongsTo(Adventures::class,"package","id");
}
public function earnings()
{
    return $this->morphMany(Earnings::class, 'source');
}

}
