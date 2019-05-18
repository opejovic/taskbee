<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Bundle;
use Faker\Generator as Faker;

$factory->define(Bundle::class, function (Faker $faker) {
	return [
		'stripe_id' => 'prod_BASIC123',
		'name' => Bundle::BASIC,
		'members_limit' => 5,
		'price' => Bundle::BASIC_PRICE,
	];
});

$factory->state(Bundle::class, 'basic', [
	'stripe_id' => 'prod_BASIC123',
	'name' => Bundle::BASIC,
	'members_limit' => 5,
	'price' => Bundle::BASIC_PRICE,
]);

$factory->state(Bundle::class, 'advanced', [
	'stripe_id' => 'prod_ADVANCED123',
	'name' => Bundle::ADVANCED,
	'members_limit' => 12,
	'price' => Bundle::ADVANCED_PRICE,
]);

$factory->state(Bundle::class, 'pro', [
	'stripe_id' => 'prod_PRO123',
	'name' => Bundle::PRO,
	'members_limit' => 20,
	'price' => Bundle::PRO_PRICE,
]);
