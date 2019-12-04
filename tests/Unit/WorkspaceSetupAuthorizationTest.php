<?php

namespace Unit;

use Tests\TestCase;
use taskbee\Models\Plan;
use taskbee\Models\User;
use taskbee\Models\Subscription;
use taskbee\Facades\AuthorizationCode;
use Illuminate\Support\Facades\Mail;
use taskbee\Mail\SubscriptionPurchasedEmail;
use taskbee\Models\WorkspaceSetupAuthorization;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkspaceSetupAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_authorize_subscriptions()
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

        Mail::assertQueued(SubscriptionPurchasedEmail::class, function ($mail) use ($subscription, $setupAuthorization) {
            return $mail->hasTo('jane@example.com')
                && $mail->authorization->id == $setupAuthorization->id
                && $mail->authorization->subscription->id == $subscription->id;
        });
    }

    /** @test */
    public function workspace_setup_authorization_can_be_retrieved_by_its_code()
    {
        $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create(['code' => 'TESTCODE123']);
        $retrievedSetupAuthorization = WorkspaceSetupAuthorization::findByCode('TESTCODE123');
        $this->assertTrue($retrievedSetupAuthorization->is($setupAuthorization));
    }

    /** @test */
    public function can_tell_if_it_has_been_used()
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
    public function can_tell_if_it_has_been_used_for_workspace_creation()
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
    public function can_tell_if_it_has_been_used_for_member_invitations()
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

    /** @test */
    public function can_tell_how_many_invites_remain()
    {
        $authorization = factory(WorkspaceSetupAuthorization::class)->create([
            'members_invited' => 2,
            'members_limit' => 5,
            'code' => 'TESTCODE456',
            'subscription_id' => 123,
        ]);

        $this->assertEquals(3, $authorization->invites_remaining);
    }

    /** @test */
    public function it_has_one_subscription()
    {
        $subscription = factory(Subscription::class)->create();
        $authorization = factory(WorkspaceSetupAuthorization::class)->create(['subscription_id' => $subscription->stripe_id]);

        $this->assertInstanceOf('taskbee\Models\Subscription', $authorization->subscription);
    }
}
