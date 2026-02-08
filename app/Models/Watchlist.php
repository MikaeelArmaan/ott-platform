<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    public $timestamps = false;

    protected $fillable = ['profile_id','content_id','created_at'];

    protected $casts = ['created_at' => 'datetime'];
}
