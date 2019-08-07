<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use taskbee\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'stripe_id' => 'cus_' . str_shuffle('RANDOMSTRING12345'),
        'email' => $faker->email,
    ];
});
