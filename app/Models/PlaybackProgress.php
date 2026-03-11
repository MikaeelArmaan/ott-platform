<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlaybackProgress extends Model
{
    use HasFactory;

    protected $table = 'playback_progress';

    protected $fillable = [
        'profile_id',
        'content_id',
        'episode_id',
        'video_asset_id',
        'position_seconds',
        'duration_seconds',
        'completion_percent',
        'last_watched_at'
    ];

    protected $casts = [
        'last_watched_at' => 'datetime'
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}