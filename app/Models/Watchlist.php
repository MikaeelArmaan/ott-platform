<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'content_id'
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
