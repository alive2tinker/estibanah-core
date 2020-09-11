<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    $types = ['short_answer','long_answer','multiple_choice','checkbox', 'file','date'];
    return [
        'id' => $faker->uuid,
        'text' => $faker->sentence,
        'description' => $faker->sentences(rand(3,6), true),
        'type' => $types[rand(0,5)]
    ];
});
