<?php

use Faker\Generator as Faker;

$factory->define(\Aeroparks\Bay\Models\Zone::class, function (Faker $faker) {
    return [
        'name'          => 'zone-',
        'slug'          => 'zone-',
        'description'   => $faker->sentence
    ];
});