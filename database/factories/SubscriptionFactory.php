<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Subscription;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'stripe_id'   => 'sub_' . str_shuffle('ABCDEFG123456'),
        'product_id'  => 'prod_FOERejda212c',
        'plan_id'     => 'plan_JUIHY2123uzl',
        'plan_name'   => 'Fake plan name',
        'customer'    => 'cus_FAKECUST123',
        'email'       => 'somebody@example.cpm',
        'billing'     => 'charge_automatically',
        'amount'      => '5995',
        'status'      => 'active',
        'start_date'  => now(),
        'expires_at'  => now()->addMonth(),
    ];
});

$factory->state(Subscription::class, 'unpaid', [
    'status' => Subscription::UNPAID,
]);

$factory->state(Subscription::class, 'past_due', [
    'status' => Subscription::PAST_DUE,
]);

$factory->state(Subscription::class, 'canceled', [
    'status' => Subscription::CANCELED,
]);