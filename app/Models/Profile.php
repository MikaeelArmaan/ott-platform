<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Content;

class Profile extends Model
{
    public $timestamps = true;
    protected $fillable = ['user_id','name','is_kids','maturity_level','pin_hash'];
    protected $casts = ['is_kids' => 'boolean'];

    public function watchlist()
    {
        return $this->belongsToMany(
            Content::class,
            'watchlists',
            'profile_id',
            'content_id'
        )->withTimestamps();
    }
}
