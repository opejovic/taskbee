<?php

namespace Tests\Unit\Billing;

use Carbon\Carbon;
use Tests\TestCase;
use App\Billing\StripePlansGateway;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group integration
 */
class StripePlansGatewayTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function can_generate_subscription_plans()
	{
		$plansGateway = new StripePlansGateway(config('services.stripe.secret'));
		\Stripe\Stripe::setApiKey(config('services.stripe.secret'));

		$created_at = Carbon::now()->unix();
		$plansGateway->generate();

		$stripePlans = \Stripe\Plan::all([
			"limit" => 10,
			"created" => [
				"gte" => $created_at,
			],
		], ['api_key' => config('services.stripe.secret')]);

		$this->assertCount(3, $stripePlans['data']);
		$this->assertArraySubset(
			[
				'Premium Monthly',
				'Standard Monthly',
				'Basic Monthly',
			],
			collect($stripePlans['data'])->pluck('nickname')->toArray(),
		);

		// Delete the product and plans from stripe after finished test
		$product = \Stripe\Product::retrieve($stripePlans['data'][0]['product']);

		collect($stripePlans['data'])->each(function ($plan) {
			$plan->delete();
		});

		$product->delete();
	}

	/** @test */
	function can_create_a_product()
	{
		$plansGateway = new StripePlansGateway(config('services.stripe.secret'));
		\Stripe\Stripe::setApiKey(config('services.stripe.secret'));

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

		$product = \Stripe\Product::retrieve($stripeProduct['data'][0]['id']);
		$product->delete();
	}

	/** @test */
	function can_create_a_plan()
	{
		$plansGateway = new StripePlansGateway(config('services.stripe.secret'));
		\Stripe\Stripe::setApiKey(config('services.stripe.secret'));

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

		collect($stripePlan['data'])->each(function ($plan) {
			$plan->delete();
		});

		$product->delete();
	}
}
