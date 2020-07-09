<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Warehouse;
use Faker\Generator as Faker;

$factory->define(
    Warehouse::class,
    function (Faker $faker) {
        return [
            'name' => $faker->name,
        ];
    }
);
