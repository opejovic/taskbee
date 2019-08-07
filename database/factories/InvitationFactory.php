<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use taskbee\Models\Invitation;
use Faker\Generator as Faker;

$factory->define(Invitation::class, function (Faker $faker) {
    return [
        'email' => 'somebody@example.com',
		'user_role' => taskbee\Models\User::MEMBER,
		'code' => 'FAKERCODE1234',
		'subscription_id' => 'sub_FAKERID123',
    ];
});
