<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** A completed volume-point → USDT redemption (admin audit list). */
class FomRedeemLog extends Model
{
    use HasFactory;

    protected $table = 'fom_redeem_logs';

    protected $fillable = ['user_id', 'option_id', 'points_spent', 'usdt_received', 'redeemed_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function option()
    {
        return $this->belongsTo(FomRedeemOption::class, 'option_id', 'id');
    }
}
