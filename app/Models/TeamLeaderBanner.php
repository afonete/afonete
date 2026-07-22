<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeaderBanner extends Model
{
    use HasFactory;

    protected $table = 'team_leader_banners';

    protected $fillable = [
        'title',
        'image_path',
        'description',
        'landing_url',
        'target_region',
        'status',
        'rejected_reason',
        'views_count',
        'downloads_count',
        'uploaded_by',
        'sort_order',
    ];
}
