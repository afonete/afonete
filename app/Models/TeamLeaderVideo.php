<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeaderVideo extends Model
{
    use HasFactory;

    protected $table = 'team_leader_videos';

    protected $fillable = [
        'title',
        'video_url',
        'video_type',
        'thumbnail',
        'description',
        'duration_seconds',
        'target_region',
        'status',
        'rejected_reason',
        'views_count',
        'uploaded_by',
        'sort_order',
    ];

    /**
     * Determine if the video URL is a YouTube link.
     */
    public function isYouTube(): bool
    {
        return str_contains($this->video_url ?? '', 'youtube.com')
            || str_contains($this->video_url ?? '', 'youtu.be');
    }

    /**
     * Extract YouTube video ID from the URL.
     */
    public function youtubeId(): ?string
    {
        if (!$this->isYouTube()) return null;
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|[^/]+[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->video_url, $match);
        return $match[1] ?? null;
    }
}
