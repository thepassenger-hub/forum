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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Channel::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text(50),
        'slug' => $faker->slug(3),
    ];
});

$factory->define(App\Thread::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->text(50),
        'description' => $faker->text(100),
        'body' => $faker->text(200),
        'user_id' => 1,
        'channel_id' => $faker->numberBetween(1, 11)
    ];
});
