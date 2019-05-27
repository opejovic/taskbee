<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Plan;
use Faker\Generator as Faker;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'amount' => 2000,
        'name' => 'Some Plan Name',
        'interval' => 'month',
        'currency' => 'eur',
        'members_limit' => 5,
        'stripe_id' => 'plan_STRIPEID123',
    ];
});

$factory->state(Plan::class, 'basic', [
    'amount' => 3995,
    'name' => 'Basic Monthly',
    'interval' => 'month',
    'currency' => 'eur',
    'members_limit' => 5,
    'stripe_id' => 'plan_BASIC123',
]);

$factory->state(Plan::class, 'standard', [
    'amount' => 6995,
    'name' => 'Standard Monthly',
    'interval' => 'month',
    'currency' => 'eur',
    'members_limit' => 10, 
    'stripe_id' => 'plan_ADVANCED123',
]);

$factory->state(Plan::class, 'premium', [
    'amount' => 9995,
    'name' => 'Premium Monthly',
    'interval' => 'month',
    'currency' => 'eur',
    'members_limit' => 15,
    'stripe_id' => 'plan_PRO123',
]);


$factory->state(Plan::class, 'perUser', [
    'amount' => 799,
    'name' => 'Per User Monthly',
    'interval' => 'month',
    'currency' => 'eur',
    'members_limit' => 1,
    'stripe_id' => 'plan_PERUSER123',
]);