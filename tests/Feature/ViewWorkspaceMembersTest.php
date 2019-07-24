<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewWorkspaceMembersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_users_can_view_their_workspace_team_members()
    {
        $user = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create(['created_by' => $user]);

        $memberA = factory(User::class)->create();
        $memberB = factory(User::class)->create();
        $otherWorkspaceMember = factory(User::class)->create();
        $workspace->addMember($memberA);
		$workspace->addMember($memberB);
        $workspace->addMember($user);

        $response = $this->actingAs($user)->get("/workspaces/{$workspace->id}/members");

        $response->assertViewIs('workspace-members.index');
        $response->assertSee($memberB->full_name);
        $response->assertSee($memberA->full_name);
        $response->assertDontSee($otherWorkspaceMember->full_name);
    }
}
