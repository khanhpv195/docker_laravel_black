<?php
namespace App\Repositories\Eloquent;

use App\Service;
use App\Repositories\BaseEloquentRepository;


class ServiceEloquentRepository extends BaseEloquentRepository
{

    /**
     * @return mixed
     */
    public function model()
    {
        return Service::class;
    }


}
