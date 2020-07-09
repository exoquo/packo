<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Stock;
use Faker\Generator as Faker;

$factory->define(Stock::class, function (Faker $faker) {
    return [
        'product_id' => factory('App\Product')->create()->id,
        'warehouse_id' => factory('App\Warehouse')->create()->id,
        'quantity' => 100,
    ];
});
