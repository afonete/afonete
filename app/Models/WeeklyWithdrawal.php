<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyWithdrawal extends Model
{
    use HasFactory;

    protected $table = 'weekly_withdrawals';

    protected $fillable = [
        'user_id','week_start','amount','transaction_no','status',
        'payment_method','admin_notes','processed_by','processed_at',
    ];

    protected $casts = [
        'week_start'   => 'date',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'pending'  => '<span class="badge badge-warning">Pending</span>',
            'approved' => '<span class="badge badge-info">Approved</span>',
            'paid'     => '<span class="badge badge-success">Paid</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
            default    => '<span class="badge badge-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}
