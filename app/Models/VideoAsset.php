<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoAsset extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'content_id',
        'episode_id',
        'source_url',
        'hls_master_url',
        'status',
        'duration_seconds',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function episode()
    {
        return $this->belongsTo(\App\Models\Episode::class);
    }
}
