<?php

use App\Models\Bundle;
use App\Models\Plan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('generate-bundles', function () {
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    // basic bundle and plan creation
    $basicBundle = \Stripe\Product::create([
		"name" => 'Basic Workspace Bundle',
		"type" => "service",
	  	"metadata" => [
	  		"members_limit" => 5,
	  		"price" => 3995,
	  	],
	]);

    $basicPlan = \Stripe\Plan::create([
        "amount" => $basicBundle['metadata']['price'],
        "interval" => "month",
        "product" => $basicBundle['id'],
        "currency" => "eur",
    ]);

    Bundle::create([
    	'stripe_id' => $basicBundle['id'],
    	'name' => $basicBundle['name'],
    	'members_limit' => $basicBundle['metadata']['members_limit'],
    	'price' => $basicBundle['metadata']['price'],
    ]);

    Plan::create([
        "amount" => $basicBundle['metadata']['price'],
        "interval" => "month",
        "product" => $basicBundle['id'],
        "currency" => "eur",
        "stripe_id" => $basicPlan['id'],
    ]);

    // advanced bundle and plan creation
    $advancedBundle = \Stripe\Product::create([
         "name" => 'Advanced Workspace Bundle',
         "type" => "service",
             "metadata" => [
                 "members_limit" => 12,
                 "price" => 6995,
             ],
    ]);

    $advancedPlan = \Stripe\Plan::create([
        "amount" => $advancedBundle['metadata']['price'],
        "interval" => "month",
        "product" => $advancedBundle['id'],
        "currency" => "eur",
    ]);

    Bundle::create([
        'stripe_id' => $advancedBundle['id'],
        'name' => $advancedBundle['name'],
        'members_limit' => $advancedBundle['metadata']['members_limit'],
        'price' => $advancedBundle['metadata']['price'],
    ]);

    Plan::create([
        "amount" => $advancedBundle['metadata']['price'],
        "interval" => "month",
        "product" => $advancedBundle['id'],
        "currency" => "eur",
        "stripe_id" => $advancedPlan['id'],
    ]);

    // pro bundle and plan creation
    $proBundle = \Stripe\Product::create([
         "name" => 'Pro Workspace Bundle',
         "type" => "service",
             "metadata" => [
                 "members_limit" => 20,
                 "price" => 9995,
             ],
    ]);

    $proPlan = \Stripe\Plan::create([
        "amount" => $proBundle['metadata']['price'],
        "interval" => "month",
        "product" => $proBundle['id'],
        "currency" => "eur",
    ]);

    Bundle::create([
        'stripe_id' => $proBundle['id'],
        'name' => $proBundle['name'],
        'members_limit' => $proBundle['metadata']['members_limit'],
        'price' => $proBundle['metadata']['price'],
    ]);

    Plan::create([
        "amount" => $proBundle['metadata']['price'],
        "interval" => "month",
        "product" => $proBundle['id'],
        "currency" => "eur",
        "stripe_id" => $proPlan['id'],
    ]);
})->describe('Generate Bundles and subscription plans');
