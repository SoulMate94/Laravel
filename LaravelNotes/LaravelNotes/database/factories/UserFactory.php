<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories   模型工厂
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
| 你的数据，数据工厂 + 连接 + 情况
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    // 返回数据， 对数据 二次 加工处理。
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
