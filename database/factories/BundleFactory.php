<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Bundle;
use Faker\Generator as Faker;

$factory->define(Bundle::class, function (Faker $faker) {
	return [
		'name' => Bundle::BASIC,
		'members_limit' => 5,
		'storage' => 2,
		'price' => Bundle::BASIC_PRICE,
		'additional_information' => 'Additional information about the advanced workspace bundle.'
	];
});

$factory->state(Bundle::class, 'basic', [
	'name' => Bundle::BASIC,
	'members_limit' => 5,
	'storage' => 2,
	'price' => Bundle::BASIC_PRICE,
	'additional_information' => 'Additional information about the basic workspace bundle.'
]);

$factory->state(Bundle::class, 'advanced', [
	'name' => Bundle::ADVANCED,
	'members_limit' => 12,
	'storage' => 4,
	'price' => Bundle::ADVANCED_PRICE,
	'additional_information' => 'Additional information about the advanced workspace bundle.'
]);

$factory->state(Bundle::class, 'pro', [
	'name' => Bundle::PRO,
	'members_limit' => 20,
	'storage' => 8,
	'price' => Bundle::PRO_PRICE,
	'additional_information' => 'Additional information about the pro workspace bundle.'
]);
