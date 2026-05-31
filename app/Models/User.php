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
use App\Models\User;
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
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'gender',
        'country',
        'utype',
        'has_paid_package',
        'has_free_package',
        'referee_id',
        'father',
        'email',
        'user',
        'contract',
        'password',
        'profile_photo_path',
        'activation',
        'gender',
        'ref_code',
        'has_request',

    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

     /**
      * Get all of the comments for the User
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
      public function have_claims():HasMany
      {
         return $this->hasMany(Claim::class);
      }
     public function deposits(): HasMany
     {
         return $this->hasMany(Deposits::class);
     }

     public function referrer()
     {
         return $this->belongsTo(User::class, 'referee_id','id');
     }

     public function WalletAddress(){
        return $this->hasOne(Wallet::class,"user","user");
     }



     public function referrals()
     {
         return $this->hasMany(User::class, 'referee_id','id');
     }

     public function ChartAccount()
     {
         return $this->hasMany(ChartAccount::class, 'user_id','id');
     }

     public function earnings()
     {
         return $this->hasMany(Earnings::class);
     }

     public static function decodeReferralId($referralId)
    {
        $referralIdParts = explode('-', $referralId);
        if (count($referralIdParts) < 3 || $referralIdParts[0] !== 'REF') {
            throw new \Exception("Invalid referral ID format");
        }

        $base36UserId = $referralIdParts[1];
        return base_convert($base36UserId, 36, 10);
    }
    public function have_activation_code()
    {
        return $this->hasOne(Activations::class,"user_id","id");
    }

    public function investments()
    {
        return $this->hasMany(Payment::class,"user","id");
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class,"user_id","id");
    }
    // public function TransferDetails()
    // {
    //     return $this->hasMany(Transaction::class,"_id","id");
    // }

    public function Transferred()
    {
        return $this->hasMany(UserTransactions::class,"sender_id","id");
    }


    public function Received()
    {
        return $this->hasMany(UserTransactions::class,"receiver_id","id");
    }

    public function DailyIncomes()
    {
        return $this->hasMany(DailyIncome::class,"user_id","id");
    }



    // my team
    public function ownedTeams()
    {
        return $this->hasMany(Teams::class, 'user_id');
    }

    /**
     * Get the teams this user is a member of.
     */
    public function teamMemberships()
    {
        return $this->hasMany(Teams::class, 'team_user_id');
    }

    public function teamMembers()
    {
        return $this->hasManyThrough(
            User::class,
            Teams::class,
            'user_id',      // Foreign key on the Team table...
            'id',           // Foreign key on the User table...
            'id',           // Local key on the User table...
            'team_user_id'  // Local key on the Team table...
        );
    }



    public function teamSide(){

        return $this->hasOne(Teams::class, 'team_user_id');
    }


    public function currentPortfolio(){

        return $this->hasMany(Portfolio::class,'user_id');
    }



}
