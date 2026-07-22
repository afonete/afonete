<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamLeaderEvent extends Model
{
    use HasFactory;

    protected $table = 'team_leader_events';

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'location',
        'zoom_link',
        'event_time',
        'status',
        'proof_submitted',
        'proof_files',
        'proof_notes',
        'proof_status',
        'event_image_1',
        'event_image_2',
        'event_done_on',
        'hotel_location',
        'country',
        'place',
        'event_date',
        'event_type',
    ];

    protected $casts = [
        'event_time'      => 'datetime',
        'event_done_on'   => 'date',
        'event_date'      => 'date',
        'proof_submitted' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
