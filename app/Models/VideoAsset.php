<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoAsset extends Model
{
    protected $fillable = [
        'content_id','episode_id','source_url','hls_master_url','status','duration_seconds'
    ];
}
