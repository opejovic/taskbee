<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use taskbee\Models\Plan;
use taskbee\Billing\StripePlansGateway;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/** @group integration */
class ClearStripeDataTest extends TestCase
{
    /** @test */
    function stripe_plans_are_cleared_when_command_is_called()
    {
        # Arrange:  Create 4 plans, 3 to be deleted and a Webhook, assert that plans exist
        $stripeGateway = (new StripePlansGateway(config('services.stripe.secret')));
        $created_at = Carbon::now()->unix();
        
        # Create Stripe Product
        $product = $stripeGateway->product();
        
        # Create 4 Stripe Plans: Basic, Standard, Premium and Dummy.
        $stripeGateway->plan(Plan::BASIC, Plan::BASIC_PRICE, Plan::BASIC_MEMBERS_LIMIT, $product);
        $stripeGateway->plan(Plan::STANDARD, Plan::STANDARD_PRICE, Plan::STANDARD_MEMBERS_LIMIT, $product);
        $stripeGateway->plan(Plan::PREMIUM, Plan::PREMIUM_PRICE, Plan::PREMIUM_MEMBERS_LIMIT, $product);
        $stripeGateway->plan('Dummy Plan', '9990', '25', $product);
    
        # Act - clear the data
        $this->artisan('clear:stripe-data');

        # Assert: All created plans and products have been deleted
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $stripePlans = \Stripe\Plan::all([
            "limit" => 10,
            "created" =>
            [
                "gte" => $created_at,
            ],
            "expand" => ['data.product']
        ]);
        
        $this->assertCount(0, $stripePlans['data']);
        $this->assertCount(0, \Stripe\Product::all(['created' => ["gte" => $created_at]])['data']);
    }
}
