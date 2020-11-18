<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'full_name'=> $faker->name,
        'phone' => $faker->phoneNumber,
        'email'=> $faker->unique()->email,
        'sex'=> 1,
        'cmt'=> rand(1,13),
        'age'=> rand(1,3),
        'country'=> $faker->country,
        'address'=> $faker->address,
        'folio_id'=> null,
        'created_at' => new DateTime,
        'updated_at' => new DateTime

    ];
});
