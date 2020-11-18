<?php


namespace App\Repositories\Eloquent;


use App\Repositories\BaseEloquentRepository;
use App\RoomType;

class RoomTypeEloquentRepository extends BaseEloquentRepository
{

    public function model()
    {
        return RoomType::class;
    }
}
