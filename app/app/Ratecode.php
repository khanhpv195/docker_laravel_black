<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratecode extends Model
{
    protected $table = 'ratecodes';
    
    protected $fillable = [
        'price','type','deleted_at'
    ];
}
