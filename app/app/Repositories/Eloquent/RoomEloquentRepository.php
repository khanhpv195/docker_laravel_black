<?php

namespace App\Repositories\Eloquent;

use App\Room;
use App\Repositories\BaseEloquentRepository;
use DB;

class RoomEloquentRepository extends BaseEloquentRepository
{

    /**
     * @return mixed
     */
    public function model()
    {
        return Room::class;
    }


    static public function searchRoomFolio($id)
    {
        $sql = "SELECT f.id,r.room_status,r.guest_status,c.full_name,f.rate_code,f.rate_override,date_arrival,date_department
                    FROM folios as f
                    LEFT JOIN rooms AS r ON f.room_id = r.id
                    LEFT JOIN customers AS c ON f.customer_id = c.folio_id
                    WHERE f.room_id = $id";
        $result = DB::select($sql);
        return $result;
    }

}
