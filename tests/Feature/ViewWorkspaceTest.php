<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewWorkspaceTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function guests_cannot_see_any_workspace_details()
	{
		$this->get("/workspaces/1")->assertRedirect('login');
	}

	/** @test */
	function workspace_is_locked_and_cannot_be_viewed_if_the_subscription_is_expired()
	{
		$subscription = factory(Subscription::class)->states('unpaid')->create();
		$workspace = factory(Workspace::class)->create(['subscription_id' => $subscription->stripe_id]);
		$user = factory(User::class)->create();
		$workspace->members()->attach($user);
		$response = $this->actingAs($user)->get("/workspaces/{$workspace->id}");

		// $response->assertStatus(423); // locked
		// $this->assertEquals($response->content(), 'Subscription exipred. Please renew your subscription.');
		$response->assertRedirect(route('subscription-expired.show', $workspace));
	}

	/** @test */
	function workspace_members_can_see_their_own_workspace_details()
	{
		$workspace = factory(Workspace::class)->create();
		$member = factory(User::class)->create();
		$workspace->members()->attach($member);

		$response = $this->actingAs($member)->get("/workspaces/{$workspace->id}");

		$response->assertStatus(200);
		$this->assertTrue($response->viewData('workspace')->is($workspace));
	}

	/** @test */
	function workspace_members_cannot_see_workspace_details_they_do_not_belong_to()
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
