<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function notifications_are_prepared_when_the_task_is_updated()
    {
        $workspace = factory(Workspace::class)->create();
        $member = factory(User::class)->create(['workspace_id' => $workspace->id]);
        $workspace->members()->attach($member);
        $workspace->members()->attach($workspace->creator);
        $task = factory(Task::class)->create([
            'name' => 'Search for droids',
            'workspace_id' => $workspace->id,
            'created_by' => $workspace->creator->id,
            'user_responsible' => $member->id,
            'status' => Task::PLANNED,
        ]);
        $this->assertCount(0, $member->unreadNotifications);
        $this->assertCount(0, $workspace->creator->unreadNotifications);

        // Act: update tasks status
        $response = $this->actingAs($workspace->creator)
            ->json('PATCH', "/workspaces/{$workspace->id}/tasks/{$task->id}", [
                'status' => Task::DONE,
            ]);

        // Assert: the notification exist for the workspace members
        $response->assertStatus(200);
        $this->assertCount(1, $workspace->creator->fresh()->unreadNotifications);
        $this->assertCount(1, $member->fresh()->unreadNotifications);
    }
}
