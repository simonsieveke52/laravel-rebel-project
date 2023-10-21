<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Quote;
use Faker\Generator as Faker;
use Faker\Provider\Address;

$factory->define(Quote::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'street_address' => $faker->address,
        'city' => $faker->city,
        'state' => $faker->state,
        'zip' => Address::postCode(),
    ];
});
