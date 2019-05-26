<?php

namespace Tests\Feature;

use App\Facades\InvitationCode;
use App\Mail\InvitationEmail;
use App\Models\Customer;
use App\Models\Invitation;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InitialWorkspaceSetupProcessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function authorized_users_can_view_initial_workspace_setup_page_with_an_unused_authorization_code()
    {
        $user = factory(User::class)->create();
        $authorization = factory(WorkspaceSetupAuthorization::class)->create([
            'admin_id' => null,
            'workspace_id' => null,
            'members_invited' => null,
            'members_limit' => 5,
            'code' => 'SAMPLEAUTHORIZATIONCODE123'
        ]);

        $response = $this->actingAs($user)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");
        $response->assertStatus(200);
        $response->assertViewIs('workspace-setup.create-workspace');

        $authorization->update([
            'workspace_id' => 1,
            'admin_id' => $user->id
        ]);

        $response = $this->actingAs($user)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");
        $response->assertStatus(200);
        $response->assertViewIs('workspace-setup.invite-members');

        $authorization->update([
            'members_invited' => 5,
            'members_limit' => 5,
        ]);

        $response = $this->actingAs($user)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");
        $response->assertStatus(404);
    }

    /** @test */
    function user_is_cannot_view_initial_workspace_setup_page_with_an_used_authorization_code()
    {
        $user = factory(User::class)->create();
        $authorization = factory(WorkspaceSetupAuthorization::class)->states('used')->create();

        $response = $this->actingAs($user)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");

        $response->assertStatus(404);
    }

    /** @test */
    function user_is_authorized_to_set_up_his_workspace_after_a_successful_bundle_purchase()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
            'admin_id' => $user->id,
            'workspace_id' => null,
            'members_invited' => 1,
            'members_limit' => 5,
            'email' => 'john@example.com',
            'code' => 'SAMPLEAUTHORIZATIONCODE123'
        ]);

        $this->actingAs($user)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123")
            ->assertStatus(200)
            ->assertViewIs('workspace-setup.create-workspace');

        $response = $this->actingAs($user)->from("/workspace-setup/SAMPLEAUTHORIZATIONCODE123")
            ->json('POST', '/workspace-setup/workspace', [
                'name' => 'Sample Workspace Name',
                'authorization_code' => $setupAuthorization->code,
            ]);

        $this->assertCount(1, Workspace::all());
        $workspace = Workspace::where('name', 'Sample Workspace Name')->first();
        $this->assertNotNull($workspace);

        $this->assertTrue($setupAuthorization->fresh()->hasBeenUsedForWorkspace());
        $response->assertRedirect('/workspace-setup/SAMPLEAUTHORIZATIONCODE123');

        $this->get('/workspace-setup/SAMPLEAUTHORIZATIONCODE123')
            ->assertViewIs('workspace-setup.invite-members');
    }

    /** @test */
    function user_is_authorized_to_invite_members_to_his_workspace_after_a_successful_workspace_setup()
    {
        Mail::fake();
        InvitationCode::shouldReceive('generate')->andReturn('TESTINVITATIONCODE123');

        $admin = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create(['created_by' => $admin->id]);

        $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
            'admin_id' => $admin->id,
            'workspace_id' => $workspace->id,
            'members_invited' => 1,
            'members_limit' => 2,
            'email' => 'john@example.com',
            'code' => 'SAMPLEAUTHORIZATIONCODE123',
            'subscription_id' => $workspace->subscription_id,
        ]);

        $this->actingAs($admin)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123")
            ->assertStatus(200)
            ->assertViewIs('workspace-setup.invite-members');

        $response = $this->actingAs($admin)->from("/workspace-setup/SAMPLEAUTHORIZATIONCODE123")
            ->json('POST', "/workspace-setup/{$workspace->id}/members", [
                'first_name' => 'Jackie',
                'last_name' => 'Doe',
                'email' => 'jackie@example.com',
                'authorization_code' => 'SAMPLEAUTHORIZATIONCODE123',
            ]);

        $invitation = Invitation::first();
        $this->assertNotNull($invitation);
        $this->assertEquals('jackie@example.com', $invitation->email);
        
        Mail::assertQueued(InvitationEmail::class, function ($mail) use ($invitation) {
            return $mail->hasTo('jackie@example.com') 
                && $mail->invitation->is($invitation);
        });

        $this->assertTrue($setupAuthorization->fresh()->hasBeenUsedForMemberInvites());
        $response->assertRedirect("/workspaces/{$workspace->id}");

        $this->get('/workspace-setup/SAMPLEAUTHORIZATIONCODE123')
            ->assertStatus(404);
    }
}
