<?php

namespace Unit;

use App\Facades\AuthorizationCode;
use App\Mail\SubscriptionPurchasedEmail;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WorkspaceSetupAuthorizationTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function can_authorize_subscriptions()
	{
		Mail::fake();
		AuthorizationCode::shouldReceive('generate')->andReturn('TESTCODE123');

	    $subscription = factory(Subscription::class)->create([
	    	'plan_id' => factory(Plan::class)->create()->stripe_id,
	    	'email' => factory(User::class)->create(['email' => 'jane@example.com'])->email
	    ]);

	    $this->assertCount(0, WorkspaceSetupAuthorization::all());

	    WorkspaceSetupAuthorization::authorize($subscription);

	    $setupAuthorization = WorkspaceSetupAuthorization::where('subscription_id', $subscription->stripe_id)->first();

	    $this->assertNotNull($setupAuthorization);

	    Mail::assertQueued(SubscriptionPurchasedEmail::class, function($mail) use ($subscription, $setupAuthorization) {
            return $mail->hasTo('jane@example.com')
                && $mail->setupAuthorization->id == $setupAuthorization->id
                && $mail->subscription->id == $subscription->id;
        });
	}

	/** @test */
	function workspace_setup_authorization_can_be_retrieved_by_its_code()
	{
	    $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create(['code' => 'TESTCODE123']);
		$retrievedSetupAuthorization = WorkspaceSetupAuthorization::findByCode('TESTCODE123');
		$this->assertTrue($retrievedSetupAuthorization->is($setupAuthorization));
	}

	/** @test */
	function can_tell_if_it_has_been_used()
	{
	    $usedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'admin_id' => 1,
	    	'workspace_id' => 1,
	    	'members_invited' => 5,
	    	'members_limit' => 5,
	    	'code' => 'TESTCODE123',
	    	'subscription_id' => 345,
	    ]);

	    $unusedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'admin_id' => null,
	    	'workspace_id' => null,
	    	'members_invited' => null,
	    	'members_limit' => 5,
	    	'code' => 'TESTCODE456',
	    	'subscription_id' => 123,
	    ]);

	    $this->assertTrue($usedSetupAuthorization->hasBeenUsed());
	    $this->assertFalse($unusedSetupAuthorization->hasBeenUsed());
	    
	}

	/** @test */
	function can_tell_if_it_has_been_used_for_workspace_creation()
	{
	    $usedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'workspace_id' => 1,
	    	'code' => 'TESTCODE123',
	    	'subscription_id' => 345,
	    ]);

	    $unusedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'workspace_id' => null,
	    	'code' => 'TESTCODE456',
	    	'subscription_id' => 123,
	    ]);

	    $this->assertTrue($usedSetupAuthorization->hasBeenUsedForWorkspace());
	    $this->assertFalse($unusedSetupAuthorization->hasBeenUsedForWorkspace());
	}

	/** @test */
	function can_tell_if_it_has_been_used_for_member_invitations()
	{
	    $usedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'members_invited' => 5,
	    	'members_limit' => 5,
	    	'code' => 'TESTCODE123',
	    	'subscription_id' => 345,
	    ]);

	    $unusedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'members_invited' => null,
	    	'members_limit' => 5,
	    	'code' => 'TESTCODE456',
	    	'subscription_id' => 123,
	    ]);

	    $this->assertTrue($usedSetupAuthorization->hasBeenUsedForMemberInvites());
	    $this->assertFalse($unusedSetupAuthorization->hasBeenUsedForMemberInvites());
	}
}
