<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Medicine;
use Faker\Generator as Faker;

$factory->define(Medicine::class, function (Faker $faker) {
    return [
        'medicine_name' =>  $faker->text('25'),
        'dosage' =>  $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100) ,
        'form' =>  $faker->randomElement(['Comprime','gelule','collyre','sirop']),
        'family' =>  $faker->text('40'),
        'Img' =>  $faker->randomElement(['doliprane.jpg','product_01.png']),
        'description' =>  $faker->text('256'),
    ];
});
