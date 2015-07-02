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
        'updated_at' => $faker->dateTimeThisMonth($max = 'now')
    ];
});

$factory->define(App\Phone::class, function ($faker) {


    return [
        'mac' => 'SEP' . strtoupper($faker->regexify('[0-9A-Fa-f]{12}')),
        'description' => $faker->realText($maxNbChars = 30, $indexSize = 2),
        'created_at' => Carbon::now(),
        'updated_at' => $faker->dateTimeThisMonth($max = 'now')
    ];
});

$factory->define(App\Itl::class, function ($faker) {

    $passFail = $faker->shuffle(['Success','Fail']);
    $failReason = false;

    if($passFail[0] == 'Fail')
    {
        $failReason = $faker->text($maxNbChars = 10);
    } else {
        $failReason = '';
    }

    return [
        'phone_id' => $faker->numberBetween($min = 1, $max = 20),
        'ip_address' => $faker->localIpv4(),
        'result' => $passFail[0],
        'failure_reason' => $failReason,
        'created_at' => Carbon::now(),
        'updated_at' => $faker->dateTimeThisMonth($max = 'now')
    ];
});
