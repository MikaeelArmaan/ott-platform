<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public $timestamps = true;
    
    protected $fillable = [
        'content_id','season_number','title','description'
    ];

    public function series()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class)->orderBy('episode_number');
    }
}
