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

    // Basic Bundle and Plan creation
    $basicBundle = \Stripe\Product::create([
		"name" => 'Basic Workspace Bundle',
		"type" => "service",
	  	"metadata" => [
	  		"members_limit" => 5,
	  	],
	]);

    $basicPlan = \Stripe\Plan::create([
        "nickname" => "Basic Monthly",
        "amount" => 3995,
        "interval" => "month",
        "product" => $basicBundle['id'],
        "currency" => "eur",
    ]);

    Bundle::create([
    	'stripe_id' => $basicBundle['id'],
    	'name' => $basicBundle['name'],
    	'members_limit' => $basicBundle['metadata']['members_limit'],
    	'price' => $basicPlan['amount'],
    ]);

    Plan::create([
        "name" => $basicPlan['nickname'],
        "amount" => $basicPlan['amount'],
        "interval" => "month",
        "product" => $basicBundle['id'],
        "currency" => "eur",
        "stripe_id" => $basicPlan['id'],
    ]);

    // Advanced Bundle and Plan creation
    $standardBundle = \Stripe\Product::create([
         "name" => 'Advanced Workspace Bundle',
         "type" => "service",
             "metadata" => [
                 "members_limit" => 10,
             ],
    ]);

    $standardPlan = \Stripe\Plan::create([
        "nickname" => "Standard Monthly",
        "amount" => 6995,
        "interval" => "month",
        "product" => $standardBundle['id'],
        "currency" => "eur",
    ]);

    Bundle::create([
        'stripe_id' => $standardBundle['id'],
        'name' => $standardBundle['name'],
        'members_limit' => $standardBundle['metadata']['members_limit'],
        'price' => $standardPlan['amount'],
    ]);

    Plan::create([
        "name" => $standardPlan['nickname'],
        "amount" => $standardPlan['amount'],
        "interval" => "month",
        "product" => $standardBundle['id'],
        "currency" => "eur",
        "stripe_id" => $standardPlan['id'],
    ]);

    // Premium Bundle and Plan creation
    $premiumBundle = \Stripe\Product::create([
         "name" => 'Premium Workspace Bundle',
         "type" => "service",
             "metadata" => [
                 "members_limit" => 20,
             ],
    ]);

    $premiumPlan = \Stripe\Plan::create([
        "nickname" => "Premium Monthly",
        "amount" => 9995,
        "interval" => "month",
        "product" => $premiumBundle['id'],
        "currency" => "eur",
    ]);

    Bundle::create([
        'stripe_id' => $premiumBundle['id'],
        'name' => $premiumBundle['name'],
        'members_limit' => $premiumBundle['metadata']['members_limit'],
        'price' => $premiumPlan['amount'],
    ]);

    Plan::create([
        "name" => $premiumPlan['nickname'],
        "amount" => $premiumPlan['amount'],
        "interval" => "month",
        "product" => $premiumBundle['id'],
        "currency" => "eur",
        "stripe_id" => $premiumPlan['id'],
    ]);

    // Generate webhook endpoint for completed checkout session. Ngrok url is for testing only.
    // In production, we would use the real POST url here.  
    \Stripe\WebhookEndpoint::create([
      "url" => config('services.ngrok.url'),
      "enabled_events" => ["checkout.session.completed"]
    ]);

})->describe('Generate Bundles and Subscription plans');



