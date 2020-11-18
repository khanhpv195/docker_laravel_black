<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'folio_id',
        'confirm_id',
        'service_id',
        'service_quality',
        'service_price',
        'booking_type',
        'price_total_booking',
        'booking_quality',
        'note',
        'rate_code',
        'customer_id',
        'rate_discount_money',
        'discount_percent',
        'deleted_at'
    ];

    const BOOKING_NEW = 1;
    const BOOKING_FINISH = 2;
    const BOOKING_CANCEL = 3;
}
