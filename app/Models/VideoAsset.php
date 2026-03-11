<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'episode_id',
        'type',
        'quality',
        'path',
        'duration',
        'mime_type',
        'size',
        'is_processed',
        'error'
    ];

    protected $casts = [
        'is_processed' => 'boolean',
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

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
