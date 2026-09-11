<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TokenSaving extends Model
{
    use HasFactory;

    protected $table = 'token_savings';

    protected $fillable = [
        'user_id',
        'amount',
        'start_date',
        'mature_date',
        'withdrawn_date',
        'withdrawn_amount',
        'status',
    ];

    protected $casts = [
        'amount'           => 'decimal:4',
        'withdrawn_amount' => 'decimal:4',
        'start_date'       => 'date',
        'mature_date'     => 'date',
        'withdrawn_date'   => 'date',
    ];

    const LOCK_MONTHS = 6;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isMatured(): bool
    {
        if ($this->status === 'withdrawn') return false;
        if ($this->status === 'matured') return true;
        return $this->mature_date && Carbon::parse($this->mature_date)->lte(Carbon::now()->startOfDay());
    }

    public function daysUntilMature(): int
    {
        if ($this->isMatured()) return 0;
        return max(0, (int) Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->mature_date), false));
    }
}
