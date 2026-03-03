<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends Model
{
    
    use HasFactory; // ✅ REQUIRED
    public $timestamps = true;
    
    protected $fillable = [
        'type',
        'title',
        'description',
        'language',
        'release_date',
        'runtime_seconds',
        'poster_url',
        'thumbnail_url',
        'backdrop_url',
        'video_url',
        'maturity_rating',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean'
    ];

    public function inWatchlists()
    {
        return $this->belongsToMany(
            Profile::class,
            'watchlists'
        );
    }

    public function videoAsset()
    {
        return $this->hasOne(\App\Models\VideoAsset::class);
    }

    public function seasons()
    {
        return $this->hasMany(\App\Models\Season::class)->orderBy('season_number');
    }

    public function episodes()
    {
        return $this->hasMany(\App\Models\Episode::class);
    }


}
