<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcceptInvitationTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function viewing_unused_invitations()
	{
		$invitation = factory(Invitation::class)->create([
			'user_id' => null,
			'code' => 'INVITATIONCODE123',
			'workspace_id' => factory(Workspace::class)->create()->id,
		]);

		$response = $this->get("/invitations/INVITATIONCODE123");

		$response->assertStatus(200);
		$response->assertViewIs('invitations.show');
		$this->assertTrue($response->viewData('invitation')->is($invitation));
	}

	/** @test */
	function viewing_used_invitations()
	{
		$invitation = factory(Invitation::class)->create([
			'user_id' => 1,
			'code' => 'TESTCODE1234',
		]);

		$response = $this->get('/invitations/TESTCODE1234');
		$response->assertStatus(404);
	}

	/** @test */
	function viewing_non_existent_invitations()
	{
		$this->get('/invitations/NONEXISTINGINVITATIONCODE')->assertStatus(404);
	}

	/** @test */
	function registering_with_a_valid_invitation_code()
	{
		$workspace = factory(Workspace::class)->create();
		$invitation = factory(Invitation::class)->create([
			'user_id' => null,
			'code' => 'INVITATIONCODE123',
			'workspace_id' => $workspace->id,
		]);

		$response = $this->json('POST', '/register-invitees', [
			'first_name' => 'Jae',
			'last_name' => 'Sremmurd',
			'email' => 'jae@example.com',
			'email_confirmation' => 'jae@example.com',
			'password' => 'password',
			'password_confirmation' => 'password',
			'invitation_code' => $invitation->code,
		]);

		$this->assertTrue($invitation->fresh()->hasBeenUsed());
		$member = $workspace->members()->where('email', 'jae@example.com')->first();
		$this->assertNotNull($member);
		$response->assertRedirect("/workspaces/{$workspace->id}/tasks");
	}

	/** @test */
	function existing_users_can_accept_invitations_for_other_workspaces()
	{
		$workspace = factory(Workspace::class)->create();
		$otherWorkspace = factory(Workspace::class)->create();
		$existingUser = factory(User::class)->create([
			'email' => 'jae@example.com',
		]);
		$otherWorkspace->addMember($existingUser);

		$invitation = factory(Invitation::class)->create([
			'email' => 'jae@example.com',
			'user_id' => null,
			'code' => 'INVITATIONCODE123',
			'workspace_id' => $workspace->id,
		]);

		$response = $this->actingAs($existingUser)->json('POST', '/accept-invitation', [
			'invitation_code' => $invitation->code,
		]);

		$this->assertTrue($invitation->fresh()->hasBeenUsed());
		$member = $workspace->members()->where('email', 'jae@example.com')->first();
		$this->assertNotNull($member);
		$this->assertEquals(2, $existingUser->workspaces->count());
	}
}
