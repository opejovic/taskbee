<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Subscription;
use App\Mail\TaskCreatedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTasksTest extends TestCase
{
    use RefreshDatabase;

    // todo: 8. Every team member can also change any task attribute or edit the name etc.

    /** @test */
    function unauthenticated_users_cannot_create_new_tasks()
    {
        $this->post("workspaces/1/tasks", [])->assertRedirect('login');
    }

    /** @test */
    function workspace_is_locked_and_tasks_cannot_be_created_if_subscription_is_expired()
    {
        $subscription = factory(Subscription::class)->states('unpaid')->create();
        $workspace = factory(Workspace::class)->create(['subscription_id' => $subscription->stripe_id]);
        $user = factory(User::class)->create();
        $workspace->members()->attach($user);

        $this->actingAs($user)->get("/workspaces/{$workspace->id}/tasks/create")
            ->assertStatus(423); // locked

        $this->actingAs($user)->post("/workspaces/{$workspace->id}/tasks")
            ->assertStatus(423); // locked
    }

    /** @test */
    function team_members_can_assign_new_tasks_to_other_members()
    {
        Mail::fake();

        $workspace = factory(Workspace::class)->create();
        $taskCreator = factory(User::class)->states('member')->create();
        $member = factory(User::class)->states('member')->create();
        $workspace->members()->attach($taskCreator);
        $workspace->members()->attach($member);
        $this->assertCount(0, Task::all());

        $response = $this->actingAs($taskCreator)
            ->json('POST', "workspaces/{$workspace->id}/tasks",
                [
                    'name'             => 'Create a YoY sales report.',
                    'user_responsible' => $member->id,
                    'start_date'       => '2019-05-12',
                    'finish_date'      => '2019-05-25',
                    'status'           => Task::PLANNED,
                ]
            );

        $response->assertStatus(201);
        $this->assertCount(1, Task::all());
        $task = $member->tasks()->where('name', 'Create a YoY sales report.')->first();
        $this->assertNotNull($task);
        $this->assertTrue($taskCreator->tasksCreated()->first()->is($task));

        Mail::assertQueued(TaskCreatedEmail::class, function ($mail) use ($task, $member) {
            return $mail->hasTo($member->email)
                && $mail->task->is($task);
        });
    }

    /** @test */
    function team_members_can_delete_any_task_belonging_to_their_workspace()
    {
        $workspace = factory(Workspace::class)->create();
        $john = factory(User::class)->create();
        $jane = factory(User::class)->create();
        $workspace->members()->attach($john);
        $workspace->members()->attach($jane);

        $johnsTask = factory(Task::class)->create([
            'created_by'       => $john->id,
            'user_responsible' => $john->id,
            'workspace_id'     => $workspace->id
        ]);

        $this->assertCount(1, Task::all());
        $response = $this->actingAs($jane)->delete("workspaces/{$workspace->id}/tasks/{$johnsTask->id}");
        $response->assertStatus(200);
        $this->assertCount(0, $john->tasks);
    }

    /** @test */
    function team_members_can_update_a_task_belonging_to_their_workspace_only_once_per_minute()
    {
        $workspace = factory(Workspace::class)->create();
        $john = factory(User::class)->create();
        $jane = factory(User::class)->create();
        $workspace->members()->attach($john);
        $workspace->members()->attach($jane);

        $johnsTask = factory(Task::class)->create([
            'created_by'       => $john->id,
            'user_responsible' => $john->id,
            'workspace_id'     => $workspace->id,
            'updated_at'       => now()->subWeek(),
        ]);

        $endpoint = "workspaces/{$workspace->id}/tasks/{$johnsTask->id}";

        $this->actingAs($jane)->json('PATCH', $endpoint, ['status' => Task::WAITING])->assertStatus(200);
        $this->assertEquals(Task::WAITING, $john->tasks->fresh()->first()->status);

        $this->actingAs($jane)->json('PATCH', $endpoint, ['status' => Task::DONE])->assertStatus(429);
        $this->assertEquals(Task::WAITING, $john->tasks->fresh()->first()->status);
    }

    /** @test */
    function task_cannot_be_created_if_the_name_of_the_task_contains_key_held_down_characters()
    {
        $workspace = factory(Workspace::class)->create();
        $taskCreator = factory(User::class)->states('member')->create();
        $workspace->members()->attach($taskCreator);
        $this->assertCount(0, Task::all());

        $response = $this->actingAs($taskCreator)
            ->json('POST', "workspaces/{$workspace->id}/tasks",
                [
                    'name'             => 'SPAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM',
                    'user_responsible' => $taskCreator->id,
                    'start_date'       => '2019-05-12',
                    'finish_date'      => '2019-05-25',
                    'status'           => Task::PLANNED,
                ]
            );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $this->assertCount(0, Task::all());
    }
}
