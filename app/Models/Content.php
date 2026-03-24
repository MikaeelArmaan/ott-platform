<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'slug',
        'original_title',
        'description',
        'short_description',
        'language',
        'country',
        'maturity_rating',
        'release_year',
        'release_date',
        'duration',
        'poster_url',
        'thumbnail_url',
        'backdrop_url',
        'logo_url',
        'imdb_rating',
        'avg_rating',
        'is_featured',
        'is_trending',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_published' => 'boolean',
        'release_date' => 'date',
        'published_at' => 'datetime'
    ];

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    public function getPosterAttribute()
    {
        return $this->poster_url
            ? asset('storage/' . $this->poster_url)
            : null;
    }

    public function getThumbnailAttribute()
    {
        return $this->thumbnail_url
            ? asset('storage/' . $this->thumbnail_url)
            : null;
    }
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function watchlistedBy()
    {
        return $this->belongsToMany(
            Profile::class,
            'watchlists',
            'content_id',
            'profile_id'
        )->withTimestamps();
    }

    public function videoAsset()
    {
        return $this->hasOne(VideoAsset::class, 'content_id');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class)
            ->orderBy('season_number');
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function watchHistories()
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'content_genre',
            'content_id',
            'genre_id'
        )->withTimestamps();
    }
}
