<?php

namespace Tests\Feature;

use Tests\TestCase;
use taskbee\Models\User;
use taskbee\Models\Workspace;
use taskbee\Models\Subscription;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_see_any_workspace_details()
    {
        $this->get("/workspaces/1")->assertRedirect('login');
    }

    /** @test */
    public function workspace_is_locked_and_cannot_be_viewed_if_the_subscription_is_expired()
    {
        $subscription = factory(Subscription::class)->states('unpaid')->create();
        $workspace = factory(Workspace::class)->create(['subscription_id' => $subscription->stripe_id]);
        $user = factory(User::class)->create();
        $workspace->addMember($user);
        $response = $this->actingAs($user)->get("/workspaces/{$workspace->id}");

        $response->assertRedirect(route('subscription-expired.show', $workspace));
    }

    /** @test */
    public function workspace_members_can_see_their_own_workspace_details()
    {
        $workspace = factory(Workspace::class)->create();
        $member = factory(User::class)->create();
        $workspace->addMember($member);

        $response = $this->actingAs($member)->get("/workspaces/{$workspace->id}");

        $response->assertStatus(200);
        $this->assertTrue($response->viewData('workspace')->is($workspace));
    }

    /** @test */
    public function workspace_members_cannot_see_workspace_details_they_do_not_belong_to()
    {
        $workspace = factory(Workspace::class)->create();

        $member = factory(User::class)->create([
            'workspace_id' => 999,
            'role' => User::MEMBER,
        ]);

        $response = $this->actingAs($member)->get("/workspaces/{$workspace->id}");

        $response->assertStatus(403);
    }
}
