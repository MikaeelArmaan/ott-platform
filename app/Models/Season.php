<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'season_number',
        'title',
        'description',
        'poster',
        'release_date',
        'is_published'
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_published' => 'boolean'
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

    public function episodes()
    {
        return $this->hasMany(Episode::class)
                    ->orderBy('episode_number');
    }
}