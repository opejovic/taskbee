<?php

namespace Tests\Unit;

use App\Billing\FakePaymentGateway;
use App\Billing\FakeSubscriptionGateway;
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
		$subGateway = new FakeSubscriptionGateway;
		$token = $subGateway->getValidTestToken();
		$subscription = $bundle->purchase('john@example.com', $token, $subGateway);

		$this->assertCount(1, Subscription::all());
		$this->assertEquals('john@example.com', $subscription->email);
		$this->assertEquals($bundle->price, $subscription->amount);
	}
}
