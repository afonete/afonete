<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $table = 'zoom_meetings';

    protected $fillable = [
        'topic',
        'zoom_link',
        'meeting_id',
        'passcode',
        'description',
        'status',
        'created_by',
    ];

    /**
     * Get the currently active Zoom meeting if status == 'active'.
     * Returns null if no active meeting or if status is 'ended'.
     */
    public static function activeMeeting(): ?self
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('zoom_meetings')) {
            try {
                \Illuminate\Support\Facades\Schema::create('zoom_meetings', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('topic')->default('Official Bifonex Live Zoom Presentation');
                    $table->text('zoom_link');
                    $table->string('meeting_id')->nullable();
                    $table->string('passcode')->nullable();
                    $table->text('description')->nullable();
                    $table->enum('status', ['active', 'ended'])->default('ended');
                    $table->unsignedBigInteger('created_by')->nullable();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {}
        }

        return self::where('status', 'active')->latest()->first();
    }
}
