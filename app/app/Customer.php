<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customers';

    protected $fillable = [
        'full_name', 'email', 'phone','sex','cmt','country','address','note','age','folio_id','birth_day'
    ];
}
