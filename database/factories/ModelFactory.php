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
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Channel::class, function (Faker\Generator $faker) {
    $name = $faker->text(50);
    return [
        'name' => $name,
        'description' => $faker->text(200),        
        'slug' => str_slug($name, '-')
    ];
});

$factory->define(App\Thread::class, function (Faker\Generator $faker) {

    $title = $faker->text(50);

    return [
        'title' => $title,
        'slug' => str_slug($title, '-'),        
        'body' => $faker->text(1000),
        'user_id' => $faker->numberBetween(1,11),
        'channel_id' => $faker->numberBetween(1, 11),
        'last_reply' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
    ];

});

$factory->define(App\Reply::class, function (Faker\Generator $faker) {

    return [
        'body' => $faker->text(100),
        'user_id' => $faker->numberBetween(1,11),
        'thread_id' => $faker->numberBetween(1, 101)
    ];
});
