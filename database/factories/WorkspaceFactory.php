<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use taskbee\Models\Workspace;
use Faker\Generator as Faker;

$factory->define(Workspace::class, function (Faker $faker) {
    return [
        'name' => $faker->catchPhrase,
        'created_by' => function() {
        	return factory(taskbee\Models\User::class)->create(['role' => taskbee\Models\User::ADMIN])->id;
        },
        'members_invited' => 1,
        'members_limit' => 5,
        'subscription_id' => function() {
        	return factory(taskbee\Models\Subscription::class)->create()->stripe_id;
        }
    ];
});
