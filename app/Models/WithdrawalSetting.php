<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalSetting extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_settings';

    protected $fillable = [
        'min_amount','min_deposit_amount','max_per_transaction','daily_limit','monthly_limit',
        'require_admin_approval','auto_withdrawals_enabled','admin_approval_threshold',
        'manual_review_risk_score','max_auto_withdrawal','hot_wallet_max_balance',
        'hot_wallet_reserve_balance','cold_wallet_address','default_trc20_min_length',
        'validate_trc20_format','notes','updated_by','allow_free_dashboard_access',
    ];

    public static function current(): self
    {
        static $cached = null;
        if ($cached === null) {
            $defaults = [
                'min_amount'                => 10,
                'min_deposit_amount'        => 10,
                'max_per_transaction'       => 10000,
                'daily_limit'               => 20000,
                'monthly_limit'             => 100000,
                'require_admin_approval'    => true,
                'auto_withdrawals_enabled'  => false,
                'admin_approval_threshold'  => 100,
                'manual_review_risk_score'  => 50,
                'max_auto_withdrawal'       => 100,
                'hot_wallet_reserve_balance'=> 100,
                'default_trc20_min_length'  => 34,
                'validate_trc20_format'     => true,
                'allow_free_dashboard_access'=> false,
            ];

            $cached = self::first() ?: self::create($defaults);
        }
        return $cached;
    }

    /** Sum of completed withdrawals today for a user */
    public static function withdrawnToday(int $userId): float
    {
        return (float) withdrawals::where('user_id', $userId)
            ->whereIn('status', ['pending','processing','completed'])
            ->whereDate('created_at', today())
            ->sum('amount');
    }

    /** Sum of completed withdrawals this calendar month for a user */
    public static function withdrawnThisMonth(int $userId): float
    {
        return (float) withdrawals::where('user_id', $userId)
            ->whereIn('status', ['pending','processing','completed'])
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->sum('amount');
    }
}
