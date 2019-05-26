<?php

namespace Tests\Unit\Subscribing;

use App\Subscriptions\StripePlansGateway;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StripePlansGatewayTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function can_generate_subscription_plans()
	{
	    $plansGateway = new StripePlansGateway(config('services.stripe.secret'));

	    $created_at = Carbon::now()->unix();
	    $plansGateway->generate();
	    
	    $stripePlans = \Stripe\Plan::all([
            "limit" => 10,
            "created" => [
            	"gte" => $created_at,
            ],
        ], ['api_key' => config('services.stripe.secret')]);

        $this->assertCount(4, $stripePlans);
        $this->assertArraySubset(
            [
                'Per User Monthly',
                'Premium Monthly',
                'Standard Monthly',
                'Basic Monthly',
            ], collect($stripePlans['data'])->pluck('nickname')->toArray(),
        );
	}

	/** @test */
	function can_create_a_product()
	{
	    $plansGateway = new StripePlansGateway(config('services.stripe.secret'));

	    $created_at = Carbon::now()->unix();
	    $plansGateway->product();
	    
	    $stripeProduct = \Stripe\Product::all([
            "limit" => 10,
            "created" => [
            	"gte" => $created_at,
            ],
        ], ['api_key' => config('services.stripe.secret')]);

        $this->assertCount(1, $stripeProduct['data']);
        $this->assertTrue(collect($stripeProduct['data'])->pluck('name')->contains('Workspace Bundle'));
	}

	/** @test */
	function can_create_a_plan()
	{
	    $plansGateway = new StripePlansGateway(config('services.stripe.secret'));

	    $created_at = Carbon::now()->unix();
	    $product = $plansGateway->product();
	    $plan = $plansGateway->plan('Basic Monthly', 3995, 5, $product);

	    $stripePlan = \Stripe\Plan::all([
            "limit" => 10,
            "created" => [
            	"gte" => $created_at,
            ],
        ], ['api_key' => config('services.stripe.secret')]);

        $this->assertCount(1, $stripePlan['data']);
        $this->assertTrue(collect($stripePlan['data'])->pluck('nickname')->contains('Basic Monthly'));
	}
}
