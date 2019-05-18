<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Plan;
use Faker\Generator as Faker;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'amount' => 2000,
        'interval' => 'month',
        'product' => 'prod_BUNDLE123',
        'currency' => 'eur',
        'stripe_id' => 'plan_STRIPEID123',
    ];
});
