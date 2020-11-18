<?php
namespace App\Repositories\Eloquent;

use App\Customer;
use App\Repositories\BaseEloquentRepository;


class CustomerEloquentRepository extends BaseEloquentRepository
{

    /**
     * @return mixed
     */
    public function model()
    {
        return Customer::class;
    }


}