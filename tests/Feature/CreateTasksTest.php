<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_cannot_create_new_tasks()
    {
        $this->post("/tasks", [])->assertRedirect('login');
    }


    /** @test */
    function team_members_can_assign_new_tasks_to_other_members()
    {
        $workspace = factory(Workspace::class)->create();
        $taskCreator = factory(User::class)->states('member')->create(['workspace_id' => $workspace->id]);
        $member = factory(User::class)->states('member')->create(['workspace_id' => $workspace->id]);
        $this->assertCount(0, Task::all());
        
        $response = $this->actingAs($taskCreator)
            ->json('POST', "workspaces/{$workspace->id}/tasks", 
                [
                    'name'               => 'Create a YoY sales report for vaccuum cleaners.',
                    'user_responsible'   => $member->id,
                    'start_date'         => Carbon::now(),
                    'finish_date'        => Carbon::now()->addWeek(),
                    'status'             => Task::PLANNED,
                ]
            );

        $response->assertStatus(201);
        $this->assertCount(1, Task::all());
        $task = $member->tasks()->where('name', 'Create a YoY sales report for vaccuum cleaners.')->first();
        $this->assertNotNull($task);
        $this->assertTrue($taskCreator->tasksCreated()->first()->is($task));
    }

    /** @test */
    function team_members_can_delete_any_task_belonging_to_their_workspace()
    {
        $workspace = factory(Workspace::class)->create();
        $john = factory(User::class)->create(['workspace_id' => $workspace->id]);
        $jane = factory(User::class)->create(['workspace_id' => $workspace->id]);
        $johnsTask = factory(Task::class)->create([
            'created_by' => $john->id,
            'user_responsible' => $john->id,
        ]);

        $this->assertCount(1, Task::all());
        $response = $this->actingAs($jane)->delete("workspaces/{$workspace->id}/tasks/{$johnsTask->id}");
        $response->assertStatus(200);
        $this->assertCount(0, $john->tasks);
    }

    // todo: 8. Every team member can also change any task attribute or edit the name etc.
}
