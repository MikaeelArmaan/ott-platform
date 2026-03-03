<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'season_id','episode_number','title','description',
        'runtime_seconds','release_date','thumbnail_url','video_url','is_published'
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function series()
    {
        return $this->season->series();
    }

    public function videoAsset()
    {
        return $this->hasOne(VideoAsset::class, 'episode_id');
    }
}
