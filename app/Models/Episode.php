<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'season_id',
        'episode_number',
        'title',
        'description',
        'duration',
        'release_date',
        'thumbnail',
        'backdrop',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'release_date' => 'date',
        'published_at' => 'datetime'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function videoAssets()
    {
        return $this->hasMany(VideoAsset::class);
    }
}