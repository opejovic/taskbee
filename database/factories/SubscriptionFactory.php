<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Subscription;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    $bundle = factory(App\Models\Bundle::class)->create();
    return [
        'stripe_id' => 'sub_FAKESTRIPEID123',
        'bundle_id' => function () use ($bundle) { 
        	return $bundle->stripe_id; // this could make our tests fail
        },
        'bundle_name' => $bundle->name,
        'customer' => 1,
        'email' => 'somebody@example.com',
        'billing' => 'charge_automatically',
        'plan_id' => $bundle->stripe_id,
        'amount' => $bundle->price,
        'status' => 'active',
        'start_date' => Carbon::now(),
        'expires_at' => Carbon::now()->addMonth(),
    ];
});

$factory->state(Subscription::class, 'expired', [
    'start_date' => now()->subMonth(),
    'expires_at' => now()->subDay(),
]);