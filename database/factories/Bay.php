<?php

use Faker\Generator as Faker;

$factory->define(\Aeroparks\Bay\Models\Bay::class, function (Faker $faker) {
    return [
        'name' => 'bay-'
    ];
});
