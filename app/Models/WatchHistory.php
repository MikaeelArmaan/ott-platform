<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WatchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'content_id',
        'episode_id',
        'watched_at',
        'watch_time_seconds',
        'completion_percent',
        'completed'
    ];

    protected $casts = [
        'watched_at' => 'datetime',
        'completion_percent' => 'float',
        'completed' => 'boolean'
    ];

    public function scopeContinueWatching($query)
    {
        return $query->where('completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function progressPercent()
    {
        return round($this->completion_percent ?? 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
