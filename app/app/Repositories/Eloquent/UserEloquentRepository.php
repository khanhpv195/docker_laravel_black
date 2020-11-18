<?php


namespace App\Repositories\Eloquent;


use App\Repositories\BaseEloquentRepository;
use App\User;

class UserEloquentRepository extends BaseEloquentRepository
{

    public function model()
    {
        return User::class;
    }
}
