<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Plan;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function can_be_built_from_stripes_subscription_and_an_email()
	{
		$this->withoutExceptionHandling();
		$email = factory(User::class)->create(['email' => 'john@example.com'])->email;
		$sub = Subscription::buildFrom($this->stripeSubscription(), $email);

		$this->assertCount(1, Subscription::all());
		$this->assertEquals(Subscription::first()->email, 'john@example.com');
	}

	/** @test */
	function it_has_invitations()
	{
		$subscription = factory(Subscription::class)->create();

		$invitation = factory(Invitation::class)->create(['subscription_id' => $subscription->id]);

		$this->assertTrue($subscription->invitation->is($invitation));
	}

	/** @test */
	function it_has_an_owner()
	{
		$user = factory(User::class)->create(['email' => 'john@example.com']);
		Subscription::buildFrom($this->stripeSubscription(), $user->email);

		$sub = Subscription::whereEmail('john@example.com')->first();

		$this->assertInstanceOf('App\Models\User', $sub->owner);
	}

	/** @test */
	function it_can_tell_if_its_expired()
	{
		$subscriptionUnpaid = factory(Subscription::class)->states('unpaid')->create();
		$subscriptionPastDue = factory(Subscription::class)->states('past_due')->create();

		$this->assertTrue($subscriptionUnpaid->isExpired());
		$this->assertTrue($subscriptionPastDue->isExpired());
	}

	/** @test */
	function it_can_tell_if_its_canceled()
	{
		$subscription = factory(Subscription::class)->states('canceled')->create();
		$this->assertTrue($subscription->isCanceled());
	}

	private function stripeSubscription()
	{
		return [
			"id" => "sub_F94NGC6CJuQuFx",
			"billing" => "charge_automatically",
			"billing_cycle_anchor" => 1558976640,
			"cancel_at" => null,
			"cancel_at_period_end" => false,
			"canceled_at" => null,
			"collection_method" => "charge_automatically",
			"created" => 1558976640,
			"current_period_end" => 1561655040,
			"current_period_start" => 1558976640,
			"customer" => "cus_F94N5Ii7jbGThi",
			"items" => [
				"object" => "list",
				"data" => [
					"quantity" => 1,
					"subscription" => "sub_F94NGC6CJuQuFx",
					"tax_rates" => [],
				],
			],
			"latest_invoice" => "in_1EemEuEZ56ycPUyuIjuE8Ofp",
			"livemode" => false,
			"metadata" => [],
			"plan" => [
				"id" => factory(Plan::class)->create()->stripe_id,
				"object" => "plan",
				"active" => true,
				"aggregate_usage" => null,
				"amount" => 3995,
				"billing_scheme" => "per_unit",
				"created" => 1558975799,
				"currency" => "eur",
				"interval" => "month",
				"interval_count" => 1,
				"livemode" => false,
				"metadata" => [
					"members_limit" => "5",
				],
				"nickname" => "Basic Monthly",
				"product" => "prod_F949TupzzC5KBl",
				"tiers" => null,
				"tiers_mode" => null,
				"transform_usage" => null,
				"trial_period_days" => null,
				"usage_type" => "licensed",
			],
			"quantity" => 1,
			"schedule" => null,
			"start" => 1558976640,
			"status" => "active",
			"tax_percent" => null,
			"trial_end" => null,
			"trial_start" => null,
		];
	}
}
