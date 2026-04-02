<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    protected $fillable = ['profile_id', 'video_asset_id'];

    public function video()
    {
        return $this->belongsTo(VideoAsset::class, 'video_asset_id');
    }
}