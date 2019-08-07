<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use taskbee\Models\Task;
use taskbee\Models\Workspace;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
    	'created_by' 		 => function () {
    		return factory(taskbee\Models\User::class)->create()->id;
    	},
        'workspace_id'       => function () {
            return factory(Workspace::class)->create()->id;
        },
        'name'               => 'Create a some fake sale report for fake product.',
        'user_responsible'   => function () {
    		return factory(taskbee\Models\User::class)->create()->id;
    	},
        'start_date'         => Carbon::now(),
        'finish_date'        => Carbon::now()->addMonth(),
        'status'             => Task::PENDING,
	];
});
