<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Content;
use App\Models\PlaybackProgress;
use App\Models\WatchHistory;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'avatar',
        'is_kids',
        'maturity_level',
        'pin_hash',
        'position'
    ];

    protected $casts = [
        'is_kids' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Profile belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Watchlist contents
    public function watchlist()
    {
        return $this->belongsToMany(
            Content::class,
            'watchlists',
            'profile_id',
            'content_id'
        )->withTimestamps();
    }

    // Playback progress (Continue Watching)
    public function playbackProgress()
    {
        return $this->hasMany(PlaybackProgress::class);
    }

    // Watch history
    public function watchHistory()
    {
        return $this->hasMany(WatchHistory::class);
    }
}
