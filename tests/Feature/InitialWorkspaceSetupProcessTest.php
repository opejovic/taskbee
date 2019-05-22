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
    function customer_can_view_initial_workspace_setup_page_with_an_unused_authorization_code()
    {
        $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
            'admin_id' => null,
            'workspace_id' => null,
            'members_invited' => null,
            'members_limit' => 5,
            'code' => 'SAMPLEAUTHORIZATIONCODE123'
        ]);

        $response = $this->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");
        $response->assertStatus(200);
        $response->assertViewIs('workspace-setup.create-admin');

        $admin = factory(User::class)->create(['role' => User::ADMIN]);
        $setupAuthorization->update(['admin_id' => $admin->id]);

        $response = $this->actingAs($admin)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");
        $response->assertStatus(200);
        $response->assertViewIs('workspace-setup.create-workspace');

        $setupAuthorization->update([
            'workspace_id' => 1,
        ]);

        $response = $this->actingAs($admin)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");
        $response->assertStatus(200);
        $response->assertViewIs('workspace-setup.invite-members');

        $setupAuthorization->update([
            'members_invited' => 5,
            'members_limit' => 5,
        ]);

        $response = $this->actingAs($admin)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");
        $response->assertStatus(404);
    }

    /** @test */
    function customer_is_cannot_view_initial_workspace_setup_page_with_an_used_authorization_code()
    {
        $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
            'admin_id' => 1,
            'workspace_id' => 1,
            'members_invited' => 5,
            'members_limit' => 5,
            'code' => 'SAMPLEAUTHORIZATIONCODE123'
        ]);

        $response = $this->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123");

        $response->assertStatus(404);
    }

    /** @test */
    function customer_is_authorized_to_set_up_his_account_after_a_successful_subscription_purchase()
    {
        $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
            'admin_id' => null,
            'workspace_id' => null,
            'members_invited' => null,
            'members_limit' => 5,
            'email' => 'john@example.com',
            'code' => 'SAMPLEAUTHORIZATIONCODE123'
        ]);

        $this->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123")
            ->assertStatus(200)
            ->assertViewIs('workspace-setup.create-admin');

        $response = $this->from("/workspace-setup/SAMPLEAUTHORIZATIONCODE123")
            ->json('POST', '/workspace-setup/admin', [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'authorization_code' => 'SAMPLEAUTHORIZATIONCODE123',
            ]);

        $this->assertCount(1, User::all());
        $admin = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($admin);

        $this->assertTrue($setupAuthorization->fresh()->hasBeenUsedForAdmin());
        $this->assertEquals(1, $setupAuthorization->fresh()->members_invited);
        $response->assertRedirect('/workspace-setup/SAMPLEAUTHORIZATIONCODE123');

        $this->get('/workspace-setup/SAMPLEAUTHORIZATIONCODE123')
            ->assertViewIs('workspace-setup.create-workspace');
    }

    /** @test */
    function customer_is_authorized_to_set_up_his_workspace_after_a_successful_account_setup()
    {
        $this->withoutExceptionHandling();
        $admin = factory(User::class)->create(['role' => User::ADMIN]);

        $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
            'admin_id' => $admin->id,
            'workspace_id' => null,
            'members_invited' => 1,
            'members_limit' => 5,
            'email' => 'john@example.com',
            'code' => 'SAMPLEAUTHORIZATIONCODE123'
        ]);

        $this->actingAs($admin)->get("/workspace-setup/SAMPLEAUTHORIZATIONCODE123")
            ->assertStatus(200)
            ->assertViewIs('workspace-setup.create-workspace');

        $response = $this->actingAs($admin)->from("/workspace-setup/SAMPLEAUTHORIZATIONCODE123")
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
    function customer_is_authorized_to_invite_members_to_his_workspace_after_a_successful_workspace_setup()
    {
        Mail::fake();
        InvitationCode::shouldReceive('generate')->andReturn('TESTINVITATIONCODE123');

        $admin = factory(User::class)->create(['role' => User::ADMIN]);
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
