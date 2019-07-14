<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function notifications_are_prepared_when_the_task_is_updated()
    {
        // Arrange: Workspace, workspace member and a task belonging to that workspace
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
            'updated_at' => now()->subDay(), // because of the wasUpdatedRecently method on the task.
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

    /** @test */
    public function users_can_fetch_their_notifications()
    {
        auth()->login($user = factory(User::class)->create());
        $notification = factory(DatabaseNotification::class)->create();

        $response = $this->json('GET', "/profiles/{$user->id}/notifications");

        $response->assertStatus(200);
        $this->assertContains($notification->toArray(), $response->json());
    }

    /** @test */
    public function users_can_mark_their_tasks_as_read()
    {
        auth()->login($user = factory(User::class)->create());
        $notification = factory(DatabaseNotification::class)->create();
        $this->assertCount(1, $user->unreadNotifications);

        $response = $this->json('DELETE', "/profiles/{$user->id}/notifications/{$notification->id}");

        $response->assertStatus(200);
        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
