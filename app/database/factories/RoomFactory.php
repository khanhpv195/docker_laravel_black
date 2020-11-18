<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Room;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'room_type' => 3,
        'images' => "https://pix8.agoda.net/hotelImages/3123799/-1/4be203eb53933c6c6b263181a4e0ce53.jpg?s=713x768",
        'room_no'=> $faker->unique()->buildingNumber,
        'description'=> $faker->text,
        'rate_code'=> 1000000,
        'room_status'=> 5,
        'created_at' => new DateTime,
        'updated_at' => new DateTime
    ];
});
