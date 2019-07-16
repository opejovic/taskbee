<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Invitation;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveMemberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_cannot_remove_members_from_any_workspace()
    {
        $response = $this->json('PATCH', "/workspaces/111/members/1234");

        $response->assertStatus(401); // unauthorized
    }

    /** @test */
    public function members_cannot_remove_members_from_any_workspace()
    {
        // Arrange: workspace, owner, a member and an used invitation
        $owner = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create(['created_by' => $owner->id]);

        $member = factory(User::class)->create(['id' => 123]);
        $josh = factory(User::class)->create(['id' => 1232]);
        $workspace->addMember($owner);
        $workspace->addMember($member);
        $workspace->addMember($josh);
        $this->assertCount(3, $workspace->fresh()->members);

        // Act: owner removes a member
        $response = $this->actingAs($member)->json(
            'PATCH',
            "/workspaces/$workspace->id/members/$josh->id"
        );

        $response->assertStatus(403);
        $this->assertCount(3, $workspace->fresh()->members);
    }

    /** @test */
    public function admin_cannot_remove_members_from_other_customers_workspaces()
    {
        // Arrange: workspace, owner, a member and an used invitation
        $maliciousUser = factory(User::class)->create();

        $owner = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create(['created_by' => $owner->id]);

        $member = factory(User::class)->create();
        $workspace->addMember($owner);
        $workspace->addMember($member);
        $this->assertCount(2, $workspace->fresh()->members);
        $this->assertEquals(1, $workspace->fresh()->members_invited);

        // Act: owner removes a member
        $response = $this->actingAs($maliciousUser)->json(
            'PATCH',
            "/workspaces/$workspace->id/members/$member->id"
        );

        $response->assertStatus(403);
        $this->assertCount(2, $workspace->fresh()->members);
        $this->assertEquals(1, $workspace->fresh()->members_invited);
    }

    /** @test */
    public function admins_can_remove_members_from_their_workspaces()
    {
        // Arrange: workspace, owner, a member and an used invitation
        $owner = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create(['created_by' => $owner->id]);
        $workspace->addMember($owner);

        factory(WorkspaceSetupAuthorization::class)->create([
            'workspace_id' => $workspace->id,
            'members_invited' => 2,
        ]);

        $member = factory(User::class)->create();
        factory(Invitation::class)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $member->id
        ]);

        $workspace->addMember($member);
        $this->assertCount(2, $workspace->fresh()->members);
        $this->assertEquals(1, $workspace->fresh()->members_invited);

        // Act: owner removes a member
        $response = $this->actingAs($owner)->json(
            'PATCH',
            "/workspaces/$workspace->id/members/$member->id"
        );

        // Assert: the member has been removed, the slot has been freed up
        $response->assertStatus(200);
        $this->assertCount(1, $workspace->fresh()->members);
        $this->assertCount(0, $workspace->fresh()->invitations);
        $this->assertEquals(0, $workspace->fresh()->members_invited);
        $this->assertEquals(1, $workspace->fresh()->authorization->members_invited);
    }

    /** @test */
    public function admins_cannot_remove_themselves_from_the_workspaces()
    {
        // Arrange: existing workspace and workspace owner
        $owner = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create(['created_by' => $owner->id]);
        $workspace->addMember($owner);

        factory(WorkspaceSetupAuthorization::class)->create([
            'workspace_id' => $workspace->id,
            'members_invited' => 2,
        ]);

        factory(Invitation::class)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $owner->id
        ]);

        // Act: admin tries to delete himself from the workspace
        $response = $this->actingAs($owner)->json(
            'PATCH',
            "/workspaces/$workspace->id/members/$owner->id"
        );

        // Assert: assert fail, not even shown on the page
        $response->assertStatus(403);
        $this->assertTrue($workspace->fresh()->members->contains($owner));
    }
}
