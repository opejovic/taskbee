<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use taskbee\Model;
use taskbee\Models\User;
use Faker\Provider\Uuid;
use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
	return [
		'id' => Uuid::uuid(),
		'type' => "taskbee\Notifications\TaskUpdated",
		'notifiable_type' => "taskbee\Models\User",
		'notifiable_id' => auth()->id() ?: factory(User::class)->create()->id,
		'data' => [
			"member" =>"Ognjen Pejovic",
			"task" =>"Lightning fast",
			"updated_status" =>"Done"
		],
		'read_at' => null,
		'created_at' => now(),
		'updated_at' => now(),
		
	];
});
