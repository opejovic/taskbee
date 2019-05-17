<?php

namespace Tests\Unit;

use App\Billing\FakePaymentGateway;
use App\Exceptions\SubscriptionExistsException;
use App\Models\Bundle;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BundleTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function can_be_purchased()
	{
		$bundle = factory(Bundle::class)->states('basic')->create();
		$paymentGateway = new FakePaymentGateway;
		$token = $paymentGateway->getValidTestToken();
		$subscription = $bundle->purchase($paymentGateway, $token, 'john@example.com');

		$this->assertCount(1, Subscription::all());
		$this->assertEquals('john@example.com', $subscription->email);
		$this->assertEquals($bundle->price, $paymentGateway->totalCharges());
	}

	/** @test */
	function can_retrieve_active_subscriptions_for_email()
	{
		$bundle = factory(Bundle::class)->states('basic')->create();
		$subscription = $bundle->addSubscriptionFor('john@example.com');

		$this->assertTrue($bundle->hasActiveSubscriptionFor('john@example.com'));
		$this->assertFalse($bundle->hasActiveSubscriptionFor('jane@example.com'));
	}
}
