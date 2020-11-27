<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Hash;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->sentence,
        'otp_code'=>$faker->sentence,
        'otp_attempt_time'=> now(),
        'active_status' => 'active',
        'login_type' => $faker->word,
        'wrong_time' => $faker->word,
        'facebook_id' => $faker->word,
        'email_id' => $faker->word,
        'apple_id' => $faker->word,
        'email_verified_at' => now(),
        'password' => Hash::make('123456'),
        'remember_token' => Str::random(10),
    ];
});
