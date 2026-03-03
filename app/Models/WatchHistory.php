<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'profile_id','content_id','episode_id',
        'position_seconds','duration_seconds','completed','last_watched_at'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'last_watched_at' => 'datetime',
    ];
}
