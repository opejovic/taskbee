<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\WorkspaceSetupAuthorization;
use Faker\Generator as Faker;

$factory->define(WorkspaceSetupAuthorization::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'customer' => 'cus_FAKECUS123',
		'code' => 'AUTHORIZATIONCODE1234',
		'subscription_id' => function () {
			return factory(App\Models\Subscription::class)->create()->id;
		},
		'plan_id' => function () {
			return factory(App\Models\Plan::class)->create()->stripe_id;
		},
        'members_limit' => 5,
    ];
});


$factory->state(WorkspaceSetupAuthorization::class, 'used', [
    'admin_id' => 1,
    'workspace_id' => 1,
    'members_invited' => 5,
    'members_limit' => 5,
    'code' => 'SAMPLEAUTHORIZATIONCODE123'
]);