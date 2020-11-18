<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'name', 'user_book','room_type', 'room_number', 'images','description','room_status','rate_code','rate_override','deleted_at','guest_status'
    ];

    const PHONG_SACH = 1;
    const PHONG_BAN = 2;
    const PHONG_OOO = 3; // phong co nguoi dat, nhung van o tiep duoc
    const PHONG_FULL = 4; // phong full
    const PHONG_TRONG = 5;

    public function folio()
    {
        return $this->belongsTo(Folio::class, 'room_id');
    }

}
