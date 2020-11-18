<?php
namespace App\Repositories\Eloquent;

use App\Ratecode;
use App\Repositories\BaseEloquentRepository;

class RateCodeEloquentRepository extends BaseEloquentRepository{

    public function model()
    {
        return Ratecode::class;
    }
}
