<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    protected $table = 'folios';

    protected $fillable = [
        'confirm_no', 'customer_id', 'status','date_arrival','date_department',
        'room_id','room_type','customer_no_adults','customer_no_young','customer_no_baby',
        'rate_code',
        'rate_override','discount','deleted_at','note','service','num_nigth','customer_name','customer_sex','price_total',
        'status_folio','price_advance','discount_money'
    ];

    const CHAC_CHAN = 1;
    const KHONG_CHAC_CHAN = 2;
    const CHECK_IN = 1;
    const CHECK_OUT = 2;
    const CHUA_DEN = 3;
    const CANCELED = 4;
    protected $orderable = [
        'id'
    ];

    public function room()
    {
        return $this->hasMany('App\Room','id','room_id');
    }
}
