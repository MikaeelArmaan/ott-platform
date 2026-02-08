<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'type','title','description','language','release_date','runtime_seconds',
        'poster_url','thumbnail_url','backdrop_url','maturity_rating','is_published'
    ];

    protected $casts = ['is_published' => 'boolean'];
}
