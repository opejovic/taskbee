<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Subscription;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    $bundle = factory(App\Models\Bundle::class)->create();
    return [
        'bundle_id' => function () use ($bundle) { 
        	return $bundle->id; 
        },
        'bundle' => $bundle->name,
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
