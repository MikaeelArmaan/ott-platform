<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id','name','is_kids','maturity_level','pin_hash'];
    protected $casts = ['is_kids' => 'boolean'];
}
