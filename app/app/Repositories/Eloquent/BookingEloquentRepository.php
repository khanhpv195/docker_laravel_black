<?php
namespace App\Repositories\Eloquent;

use App\Booking;
use App\Repositories\BaseEloquentRepository;


class BookingEloquentRepository extends BaseEloquentRepository
{

    /**
     * @return mixed
     */
    public function model()
    {
        return Booking::class;
    }


}
