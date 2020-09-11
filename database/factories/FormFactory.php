<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Form;
use Faker\Generator as Faker;

$factory->define(Form::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'title' => $faker->sentence,
        'description' => $faker->sentences(rand(3,6), true),
        'user_id' => 1
    ];
});
