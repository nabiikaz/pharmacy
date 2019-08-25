<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Supplier;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'supplier_name' =>  $faker->text('25'),
        'address' =>  $faker->text('50') ,
        'email' =>  $faker->email(),
        
    ];
});
