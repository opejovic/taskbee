<?php

namespace Tests\Unit\Billing;

use App\Billing\PaymentFailedException;
use App\Billing\StripeSubscriptionGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/** 
* @group integration
*/
class StripeSubscriptionGatewayTest extends TestCase
{
	use RefreshDatabase;

	protected function setUp(): void
	{
		parent::setUp();
	    $this->subGateway = new StripeSubscriptionGateway(config('services.stripe.secret'));
	}

	/** @test */
	function customer_is_created_if_a_valid_payment_token_is_provided()
	{
	    $customer = $this->subGateway->createCustomer('jane@example.com', $this->validToken());

		$this->assertNotNull(\Stripe\Customer::retrieve(
				$customer->id, 
				['api_key' => config('services.stripe.secret')]
			)
		);
	}

	/** @test */
	function customer_is_not_created_if_a_invalid_payment_token_is_provided()
	{
		$customer = $this->subGateway->createCustomer('john@example.com', $this->validToken());
		
		try {
	    	$this->subGateway->createCustomer('jane@example.com', "invalid-token");
		} catch (PaymentFailedException $e) {
			$this->assertCount(0, $this->newCustomersSince($customer));
			return;
		}

		$this->fail("Customer created even though the token provided was invalid!");
	}

	/** @test */
	function cannot_create_two_customers_with_the_same_email()
	{
		// since this uses RefreshDatabase, it wont persist a customer to db table, 
		// and the check for the existing customer in our local db will always return false.. 
		//(So if we have a customer with email jonh@example.com in our stripe customers, 
		// running this test will create another customer with john@example.com email. 
		// But in production this will work.)

	    $customer = $this->subGateway->createCustomer('john@example.com', $this->validToken());

		$this->assertNotNull(\Stripe\Customer::retrieve(
			$customer->id,
			['api_key' => config('services.stripe.secret')]
		));

		$this->subGateway->createCustomer('john@example.com', $this->validToken());

		$this->assertCount(0, $this->newCustomersSince($customer));
	}

	/** @test */
	function subscription_to_a_bundle_can_be_created_for_a_customer()
	{
	    $customer = $this->subGateway->createCustomer('john@example.com', $this->validToken());

	    // Bundles and plans are created when the app admin runs the artisan command generate-bundles.
	    $bundle = $this->createBundle();
	    $plan = $this->createPlanFor($bundle);

	    $sub = $this->subGateway->createSubscriptionFor($customer, $plan);

	    $this->assertEquals(2500, $sub['plan']['amount']);
	   	$this->assertEquals('active', $sub->status);
	   	$this->assertEquals($customer->id, $sub['customer']);
	}

	private function validToken()
	{
		return \Stripe\Token::create([
			'card' => [
				'number' => '4242424242424242',
				'exp_month' => 12,
				'exp_year' => date('Y') + 1,
				'cvc' => '123'
			]
		], ['api_key' => config('services.stripe.secret')])['id'];
	}

	private function newCustomersSince($customer)
	{
		return \Stripe\Customer::all(
			[
				"limit" => 3,
				"ending_before" => $customer->id
			],
			['api_key' => config('services.stripe.secret')]
		)['data'];
	}

	private function createBundle()
	{
		return \Stripe\Product::create([
			"name" => 'Some Sample Workspace Bundle 3',
			"type" => "service",
	  		"metadata" => [
	  			"members_limit" => 5,
	  			"price" => 2500,
	  		],
		], ['api_key' => config('services.stripe.secret')]);
	}

	private function createPlanFor($bundle)
	{
		return \Stripe\Plan::create([
	        "amount" => $bundle['metadata']['price'],
	        "interval" => "month",
	        "product" => $bundle['id'],
	        "currency" => "eur",
    	], ['api_key' => config('services.stripe.secret')]);
	}
}
