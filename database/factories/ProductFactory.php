<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Domain\Products\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => Str::random(30),
        'price' => $faker->randomFloat(2, 1, 1000),
    ];
});
