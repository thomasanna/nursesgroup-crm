<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Staff::class, function (Faker $faker) {
    return [
        'forname' =>$faker->name,
        'surname' =>$faker->name,
        'categoryId' =>1,
        'email' =>$faker->safeEmail,
        'mobile' =>$faker->numberBetween($min = 9876453423, $max = 9987674532),
        'address' =>$faker->address,
        'gender' =>$faker->numberBetween($min = 1, $max = 2),
        'bandId' =>1,
        'modeOfTransport' =>1,
        'paymentMode' =>$faker->numberBetween($min = 1, $max = 2),
        'zoneId' =>1,
    ];
});
