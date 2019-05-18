<?php

namespace Tests\Unit\Billing;

use App\Billing\FakeSubscriptionGateway;
use App\Billing\PaymentFailedException;
use App\Models\Bundle;
use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FakeSubscriptionGatewayTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function customer_is_created_if_a_valid_payment_token_is_provided()
	{
	    $subGateway = new FakeSubscriptionGateway;
	    $customer = $subGateway->createCustomer('jane@example.com', $subGateway->getValidTestToken());

	    $this->assertEquals(1, Customer::count());
	    $this->assertTrue(Customer::first()->is($customer));
	}

	/** @test */
	function customer_is_not_created_if_invalid_payment_token_is_provided()
	{
		try {
		    $subGateway = new FakeSubscriptionGateway;
		    $customer = $subGateway->createCustomer('jane@example.com', "invalid-token");
		} catch (PaymentFailedException $e) {
		    $this->assertEquals(0, Customer::count());
		    return;
		}

		$this->fail("Customer created even though the invalid token was provided");
	}

	/** @test */
	function customer_is_not_created_if_customer_with_same_email_address_already_exists()
	{
		Customer::create(['email' => 'jane@example.com']);
		$this->assertEquals(1, Customer::count());
		
	    $subGateway = new FakeSubscriptionGateway;
	    $customer = $subGateway->createCustomer('jane@example.com', $subGateway->getValidTestToken());
	    $this->assertEquals(1, Customer::count());
	}

	/** @test */
	function subscription_plan_is_created_for_an_existing_customer()
	{
		$bundle = factory(Bundle::class)->states('basic')->create(['price' => 3995]);
	    $subGateway = new FakeSubscriptionGateway;
	    $customer = $subGateway->createCustomer('jane@example.com', $subGateway->getValidTestToken());

	    $subscription = $subGateway->createSubscriptionFor($customer, $bundle);

	    $this->assertCount(1, Subscription::all());
	    $this->assertTrue(Subscription::first()->is($subscription));
	    $this->assertEquals('jane@example.com', Subscription::first()->email);
	    $this->assertEquals(3995, $subscription->amount);
	}

}
