<?php

use App\Models\Plan;
use App\Billing\StripePlansGateway;

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

Artisan::command('generate-plans', function () {
    
    if (Plan::count() > 0) {
        $this->warn(
            'Looks like you already have plans created. Please check your database, there should be no plans there prior to running this command.'
        );
        return;
    }

    $this->info("Preparing.. please wait.");
    
    // Create Stripe product and subscription plans
    (new StripePlansGateway(config('services.stripe.secret')))->generate();

    if (Plan::count() == 3) {
        $this->info('Done!');
    } else {
        $this->error('Something went wrong.');
    }

})->describe('Generate subscription plans. Run only once, at the beggining of the journey.');

Artisan::command('stripe-webhook', function () {
    
    \Stripe\WebhookEndpoint::create([
        "url" => config('services.ngrok.url') . "/stripe-webhook",
        "enabled_events" => [
            "customer.subscription.created",
            "customer.subscription.updated",
            "customer.subscription.deleted",
            "invoice.payment_succeeded",
            "checkout.session.completed",
        ]
    ], ['api_key' => config('services.stripe.secret')]);

})->describe('Generate a stripe webhook.');



