<?php

namespace Tests\Unit;

use App\Models\Bundle;
use App\Models\Subscription;
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

	    $this->assertNull(Subscription::where('email', 'john@example.com')->first());
	    $this->assertCount(0, Subscription::all());
	}

	/** @test */
	function can_be_cast_to_array()
	{
	    $subscription = factory(Subscription::class)->create([
	    	'email' => 'john@example.com',
	    	'bundle' => 'basic',
	    	'amount' => 3995,
	    ]);

	    $result = $subscription->toArray();

	    $this->assertEquals([
	    	'email' => 'john@example.com',
	    	'bundle' => 'basic',
	    	'amount' => 3995,	
	    ], $result);
	}
}
