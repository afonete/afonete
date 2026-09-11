<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcpTokenRelease extends Model
{
    use HasFactory;

    protected $table = 'fcp_token_releases';

    protected $fillable = [
        'user_id',
        'payment_id',
        'package_id',
        'total_tokens',
        'monthly_amount',
        'total_months',
        'months_released',
        'released_tokens',
        'start_date',
        'next_release_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'total_tokens'     => 'decimal:4',
        'monthly_amount'   => 'decimal:4',
        'released_tokens'  => 'decimal:4',
        'total_months'     => 'integer',
        'months_released'  => 'integer',
        'start_date'       => 'date',
        'next_release_date'=> 'date',
        'end_date'         => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->belongsTo(Paymodel::class, 'payment_id');
    }

    public function package()
    {
        return $this->belongsTo(FCpackage::class, 'package_id');
    }
}
