<?php

namespace App\Repositories\Eloquent;

use App\Folio;
use App\Repositories\BaseEloquentRepository;
use DB;

class FolioEloquentRepository extends BaseEloquentRepository
{

    /**
     * @return mixed
     */
    public function model()
    {
        return Folio::class;
    }


    public function getAllFolio()
    {
        $sql = "select f.id,f.room_type,f.room_id,f.confirm_no,f.status,f.customer_name,f.customer_sex,f.date_arrival,f.date_department,
                f.service,f.status_folio,f.price_total, r.room_number,f.rate_code, f.rate_override,f.price_advance  from folios as f
        LEFT JOIN rooms as r ON f.room_id = r.id";
        $result = DB::select($sql);
        return $result;
    }

}
