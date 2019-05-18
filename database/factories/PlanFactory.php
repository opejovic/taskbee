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

$factory->state(Plan::class, 'basic', [
    'amount' => 3995,
    'interval' => 'month',
    'product' => 'prod_BASIC123',
    'currency' => 'eur',
    'stripe_id' => 'plan_BASIC123',
]);

$factory->state(Plan::class, 'advanced', [
    'amount' => 6995,
    'interval' => 'month',
    'product' => 'prod_ADVANCED123',
    'currency' => 'eur',
    'stripe_id' => 'plan_ADVANCED123',
]);

$factory->state(Plan::class, 'pro', [
    'amount' => 9995,
    'interval' => 'month',
    'product' => 'prod_PRO123',
    'currency' => 'eur',
    'stripe_id' => 'plan_PRO123',
]);