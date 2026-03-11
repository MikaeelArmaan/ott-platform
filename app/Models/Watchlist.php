<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'content_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
