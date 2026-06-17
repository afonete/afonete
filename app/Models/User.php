<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Deposits;
use App\Models\Earnings;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Activations;
use App\Models\Teams;
use App\Models\Portfolio;
use App\Models\DailyIncome;
use App\Models\ChartAccount;
use App\Models\Wallet;
use App\Models\user_transactions as UserTransactions;
use App\Models\Claim;
use App\Models\ReferralBonus;
use App\Models\UserRank;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name','phone','gender','country','utype',
        'has_paid_package','has_free_package',
        'referee_id','father','email','user','contract','password',
        'profile_photo_path','activation','gender','ref_code','has_request',
    ];

    protected $hidden = [
        'password','remember_token','two_factor_recovery_codes','two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['profile_photo_url'];

    /* ===========================================================
     *  REFERRAL RELATIONSHIPS
     * =========================================================== */

    public function referrals()
    {
        return $this->hasMany(User::class, 'referee_id', 'id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referee_id', 'id');
    }

    /** All referral bonuses received by this user (any source) */
    public function referralBonuses(): HasMany
    {
        return $this->hasMany(ReferralBonus::class, 'user_id');
    }

    /** Bonuses this user generated for their upline (because of their own purchases) */
    public function generatedBonuses(): HasMany
    {
        return $this->hasMany(ReferralBonus::class, 'source_user_id');
    }

    /** User rank applications */
    public function userRanks(): HasMany
    {
        return $this->hasMany(UserRank::class);
    }

    /** Approved ranks only (highest level is the "current" rank) */
    public function approvedRanks()
    {
        return $this->userRanks()->where('status', 'approved')->orderByDesc('rank_level');
    }

    /**
     * Walk the referral upline up to N levels.
     *
     * @param  int  $maxLevels  3 for 10% / 1% / 0.5%
     * @return array  [['user' => User|null, 'level' => 1], …]
     */
    public function uplineChain(int $maxLevels = 3): array
    {
        $chain = [];
        $current = $this->referrer;
        $level = 1;
        while ($current && $level <= $maxLevels) {
            $chain[] = ['user' => $current, 'level' => $level];
            $current = $current->referrer;
            $level++;
        }
        // Pad missing levels with null so the index always lines up
        while (count($chain) < $maxLevels) {
            $chain[] = ['user' => null, 'level' => ++$level - 1];
        }
        return $chain;
    }

    /* ===========================================================
     *  REFERRAL INVESTMENT METRICS (used for rank criteria)
     * =========================================================== */

    /**
     * Sum of active VENTURE package amounts of direct referrals.
     * Only counts packages that are paid (status=1) and not expired.
     */
    public function directReferralActiveInvestment(): float
    {
        $directIds = $this->referrals()->pluck('id');
        if ($directIds->isEmpty()) return 0.0;

        return (float) Payment::whereIn('user', $directIds)
            ->where('category', 'VENTURE')
            ->where('status', 1)
            ->where('is_expired', false)
            ->sum('amount');
    }

    /**
     * Sum of active VENTURE package amounts of ALL referrals
     * (direct + indirect), recursively up to N levels (default 10).
     */
    public function totalReferralActiveInvestment(int $maxDepth = 10): float
    {
        $total = 0.0;
        $directIds = $this->referrals()->pluck('id')->all();
        $visited = [];
        $depth = 0;
        while (!empty($directIds) && $depth < $maxDepth) {
            $visited = array_merge($visited, $directIds);
            $total += (float) Payment::whereIn('user', $directIds)
                ->where('category', 'VENTURE')
                ->where('status', 1)
                ->where('is_expired', false)
                ->sum('amount');

            $nextIds = User::whereIn('referee_id', $directIds)
                ->whereNotIn('id', $visited)
                ->pluck('id')->all();
            $directIds = $nextIds;
            $depth++;
        }
        return $total;
    }

    /** Count of active direct referrals (those with at least one active VENTURE package) */
    public function activeDirectReferralCount(): int
    {
        $directIds = $this->referrals()->pluck('id');
        if ($directIds->isEmpty()) return 0;

        return (int) User::whereIn('id', $directIds)
            ->whereHas('investments', function ($q) {
                $q->where('category', 'VENTURE')
                  ->where('status', 1)
                  ->where('is_expired', false);
            })->count();
    }

    /** Count of direct referrals holding a specific approved rank */
    public function directReferralsWithRank(string $rankSlug): int
    {
        $directIds = $this->referrals()->pluck('id');
        if ($directIds->isEmpty()) return 0;

        return (int) UserRank::whereIn('user_id', $directIds)
            ->where('rank_slug', $rankSlug)
            ->where('status', 'approved')
            ->count();
    }

    /**
     * Get the 10 (or N) active direct refs whose investment is ≥ threshold.
     * Used for Associate Manager weekly bonus.
     */
    public function qualifyingDirectRefsForAssociateManager(int $countNeeded = 10): array
    {
        $rank = RankSetting::where('slug', 'associate_manager')->first();
        $threshold = $rank ? (float) $rank->am_per_user_min_investment : 500000;
        $needed    = $rank ? (int) $rank->am_min_active_direct_investment_users : $countNeeded;

        $directIds = $this->referrals()->pluck('id');
        if ($directIds->isEmpty()) return [];

        $refInvestments = Payment::whereIn('user', $directIds)
            ->where('category', 'VENTURE')
            ->where('status', 1)
            ->where('is_expired', false)
            ->selectRaw('user, SUM(amount) AS total_investment')
            ->groupBy('user')
            ->having('total_investment', '>=', $threshold)
            ->orderByDesc('total_investment')
            ->limit($needed)
            ->get();

        return $refInvestments->pluck('user')->all();
    }

    /* ===========================================================
     *  REFERRAL BONUS TOTALS (cached helpers)
     * =========================================================== */

    public function referralBonusTotals(): array
    {
        return ReferralBonus::totalsForUser($this->id);
    }

    /** Current highest approved rank, or null */
    public function currentRank(): ?UserRank
    {
        return $this->approvedRanks()->first();
    }

    /** Decoded referral id (REF-XXXX-YYYY format) */
    public static function decodeReferralId($referralId)
    {
        $referralIdParts = explode('-', $referralId);
        if (count($referralIdParts) < 3 || $referralIdParts[0] !== 'REF') {
            throw new \Exception("Invalid referral ID format");
        }
        $base36UserId = $referralIdParts[1];
        return base_convert($base36UserId, 36, 10);
    }

    /* ===========================================================
     *  EXISTING RELATIONSHIPS (unchanged)
     * =========================================================== */

    public function have_claims(): HasMany { return $this->hasMany(Claim::class); }
    public function deposits(): HasMany { return $this->hasMany(Deposits::class); }
    public function WalletAddress(){ return $this->hasOne(Wallet::class,"user","user"); }
    public function ChartAccount() { return $this->hasMany(ChartAccount::class, 'user_id','id'); }
    public function earnings() { return $this->hasMany(Earnings::class); }
    public function have_activation_code() { return $this->hasOne(Activations::class,"user_id","id"); }
    public function investments() { return $this->hasMany(Payment::class,"user","id"); }
    public function transactions() { return $this->hasMany(Transaction::class,"user_id","id"); }
    public function Transferred() { return $this->hasMany(UserTransactions::class,"sender_id","id"); }
    public function Received() { return $this->hasMany(UserTransactions::class,"receiver_id","id"); }
    public function DailyIncomes() { return $this->hasMany(DailyIncome::class,"user_id","id"); }
    public function ownedTeams() { return $this->hasMany(Teams::class, 'user_id'); }
    public function teamMemberships() { return $this->hasMany(Teams::class, 'team_user_id'); }

    public function teamMembers()
    {
        return $this->hasManyThrough(
            User::class, Teams::class,
            'user_id','id','id','team_user_id'
        );
    }
    public function teamSide() { return $this->hasOne(Teams::class, 'team_user_id'); }
    public function currentPortfolio() { return $this->hasMany(Portfolio::class,'user_id'); }
}
