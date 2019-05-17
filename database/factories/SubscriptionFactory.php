<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Subscription;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    $bundle = factory(App\Models\Bundle::class)->create();
    return [
        'bundle_id' => function () use ($bundle) { 
        	return $bundle->id; 
        },
        'bundle' => $bundle->name,
        'amount' => $bundle->price,
        'email' => 'somebody@example.com',
    ];
});
