<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Carbon\Carbon;

$factory->define(App\User::class, function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});

$factory->define(App\Phone::class, function ($faker) {


    return [
        'mac' => 'SEP' . strtoupper($faker->regexify('[0-9A-Fa-f]{12}')),
        'description' => $faker->text($maxNbChars = 30),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});

$factory->define(App\Itl::class, function ($faker) {


    return [
        'phone_id' => $faker->numberBetween($min = 1, $max = 20),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
