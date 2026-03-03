<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaybackProgress extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'content_id',
        'position_seconds',
        'duration_seconds'
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
