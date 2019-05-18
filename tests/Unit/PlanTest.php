<?php

namespace Tests\Unit;

use App\Billing\FakeSubscriptionGateway;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlanTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function plan_can_be_purchased()
	{
		$plan = factory(Plan::class)->create(['amount' => 3500]);
		$subGateway = new FakeSubscriptionGateway;
		$token = $subGateway->getValidTestToken();
		$subscription = $plan->purchase('john@example.com', $token, $subGateway);

		$this->assertCount(1, Subscription::all());
		$this->assertEquals('john@example.com', $subscription->email);
		$this->assertEquals(3500, $subscription->amount);
	}
}
