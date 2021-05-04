<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, static function (Faker\Generator $faker) {
    return [
        'created_at' => $faker->dateTime,
        'email' => $faker->email,
        'email_verified_at' => $faker->dateTime,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'updated_at' => $faker->dateTime,
        'username' => $faker->sentence,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Client::class, static function (Faker\Generator $faker) {
    return [
        'created_at' => $faker->dateTime,
        'lastname' => $faker->sentence,
        'name' => $faker->firstName,
        'updated_at' => $faker->dateTime,
        'user_id' => $faker->sentence,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Vehicle::class, static function (Faker\Generator $faker) {
    return [
        'capacity' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'model' => $faker->sentence,
        'reg_number' => $faker->sentence,
        'updated_at' => $faker->dateTime,
        'vehicle_type' => $faker->sentence,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Driver::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Driver::class, static function (Faker\Generator $faker) {
    return [
        'balance' => $faker->randomNumber(5),
        'bankAccount' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'jmbg' => $faker->sentence,
        'name' => $faker->firstName,
        'onStandby' => $faker->boolean(),
        'personalVehicle' => $faker->boolean(),
        'surname' => $faker->lastName,
        'updated_at' => $faker->dateTime,
        'user_id' => $faker->sentence,
        'vehicle_id' => $faker->sentence,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Rate::class, static function (Faker\Generator $faker) {
    return [
        'base_rate' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'rate_increment' => $faker->randomNumber(5),
        'type' => $faker->sentence,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Ride::class, static function (Faker\Generator $faker) {
    return [
        'client_id' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'driver_id' => $faker->sentence,
        'end_loc_latitude' => $faker->sentence,
        'end_loc_longitude' => $faker->sentence,
        'start_loc_latitude' => $faker->sentence,
        'start_loc_longitude' => $faker->sentence,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Transaction::class, static function (Faker\Generator $faker) {
    return [
        'created_at' => $faker->dateTime,
        'price' => $faker->randomFloat,
        'rate_id' => $faker->sentence,
        'ride_duration' => $faker->time(),
        'ride_id' => $faker->sentence,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Message::class, static function (Faker\Generator $faker) {
    return [
        'client_id' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'driver_id' => $faker->sentence,
        'msg_content' => $faker->sentence,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
