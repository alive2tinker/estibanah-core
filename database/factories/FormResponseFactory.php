<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FormResponse;
use Faker\Generator as Faker;

$factory->define(FormResponse::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid
    ];
});
