<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewWorkspaceMembersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function authorized_users_can_view_their_workspace_team_members()
    {
        $user = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create(['created_by' => $user]);

        $memberA = factory(User::class)->create();
        $memberB = factory(User::class)->create();
        $workspace->members()->attach($memberA);
        $workspace->members()->attach($memberB);

        $response = $this->actingAs($user)->get("/workspaces/{$workspace->id}/members");

        $response->assertViewIs('workspace-members.index');
        $response->assertViewHas('members');
        $this->assertTrue($response->viewData('members')->contains($memberA));
        $this->assertTrue($response->viewData('members')->contains($memberB));
        $response->assertSee($memberB->full_name);
        $response->assertSee($memberA->full_name);
    }
}
