<?php

namespace Tests\Unit;

use App\Models\Bundle;
use App\Models\Invitation;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function can_be_cancelled()
	{
	    $subscription = factory(Subscription::class)->create([
	    	'email' => 'john@example.com',
	    ]);
	    $this->assertCount(1, Subscription::all());

	    $subscription->cancel();

	    $this->assertEquals(Carbon::now()->format('Y-m-d'), $subscription->cancelled_at->format('Y-m-d'));
	    $this->assertEquals('cancelled', $subscription->status);
	}

	/** @test */
	function can_be_cast_to_array()
	{
	    $subscription = factory(Subscription::class)->create([
	    	'email' => 'john@example.com',
	    	'bundle_name' => 'Some Workspace Bundle Name',
	    	'amount' => 3995,
	    	'status' => 'active',
	    	'start_date' => Carbon::now(),
	    	'expires_at' => Carbon::now()->addMonth(),
	    ]);

	    $result = $subscription->toArray();

	    $this->assertEquals([
	    	'email' => 'john@example.com',
	    	'bundle_name' => 'Some Workspace Bundle Name',
	    	'amount' => 3995,
	    	'status' => 'active',
	    	'start_date' => Carbon::now()->format('Y-m-d'),
	    	'expires_at' => Carbon::now()->addMonth()->format('Y-m-d'),
	    ], $result);
	}

	/** @test */
	function it_has_invitations()
	{
	    $subscription = factory(Subscription::class)->create();

	    $invitation = factory(Invitation::class)->create(['subscription_id' => $subscription->id]);

	    $this->assertTrue($subscription->invitation->is($invitation));
	}

	/** @test */
	function it_belongs_to_a_bundle()
	{
	    $subscription = factory(Subscription::class)->create();
	    $this->assertInstanceOf('App\Models\Bundle', $subscription->bundle);
	    
	}
}
