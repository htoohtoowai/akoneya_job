<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobType;
use Faker\Generator as Faker;

$factory->define(JobType::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
