<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Plan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/** 
 * @group integration
 */
class ProductsAndPlansCreationTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function initial_product_and_plans_creation()
	{
		// Predefined subscription plans for the application
		$plans = [
			'basic' => [
				'name' => 'Basic Monthly',
				'members_limit' => 5, // quantity
				'price' => 799,
			],

			'standard' => [
				'name' => 'Standard Monthly',
				'members_limit' => 10, // quantity
				'price' => 699,
			],

			'premium' => [
				'name' => 'Premium Monthly',
				'members_limit' => 15, // quantity
				'price' => 499,
			],
		];

		// Act: type console command (generate-plans)
		$created_at = Carbon::now()->unix();
		$this->artisan('generate-plans');

		// Assert: the subscription plans exist in the db and on the stripe server.
		$this->assertCount(3, Plan::all());
		$this->assertNotNull($basicPlan = Plan::whereName('Basic Monthly')->first());
		$this->assertNotNull($standardPlan = Plan::whereName('Standard Monthly')->first());
		$this->assertNotNull($premiumPlan = Plan::whereName('Premium Monthly')->first());

		\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
		$stripePlans = \Stripe\Plan::all([
			"limit" => 10,
			"created" =>
			[
				"gte" => $created_at,
			],
			"expand" => ['data.product']
		]);
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
		$product = \Stripe\Product::retrieve($stripePlans['data'][0]['product']['id']);

		collect($stripePlans['data'])->each(function ($plan) {
			$plan->delete();
		});

		$product->delete();
	}
}
