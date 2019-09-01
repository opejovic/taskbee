<?php

namespace Tests\Feature;

use Tests\TestCase;
use taskbee\Models\Task;
use taskbee\Models\User;
use taskbee\Models\Workspace;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTasksTest extends TestCase
{
    use RefreshDatabase;

    private $member;
    private $workspace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = factory(User::class)->create();
        $this->workspace = factory(Workspace::class)->create();
        $this->workspace->addMember($this->member);
    }

    /** @test */
    public function workspace_members_can_see_all_workspace_tasks_and_their_properties()
    {
        $don = factory(User::class)->create();
        $jackie = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'workspace_id' => $this->workspace->id
        ]);

        $this->workspace->addMember($don);
        $this->workspace->addMember($jackie);


        $this->actingAs($don)->get("/workspaces/{$this->workspace->id}/tasks")
            ->assertSee($task->name)
            ->assertSee($task->creator->full_name)
            ->assertSee($task->assignee->full_name)
            ->assertSee($task->status)
            ->assertSee($task->start_date)
            ->assertSee($task->finish_date);

        $this->actingAs($jackie)->get("/workspaces/{$this->workspace->id}/tasks")
            ->assertSee($task->name)
            ->assertSee($task->creator->full_name)
            ->assertSee($task->assignee->full_name)
            ->assertSee($task->status)
            ->assertSee($task->start_date)
            ->assertSee($task->finish_date);
    }

    /** @test */
    public function workspace_members_can_see_tasks_they_are_responsible_for()
    {
        $membersTask = factory(Task::class)->create([
            'name' => 'Go to the store.',
            'workspace_id' => $this->workspace->id,
            'user_responsible' => $this->member->id
        ]);

        $otherTask = factory(Task::class)->create([
            'name' => 'Clean your room.',
            'workspace_id' => $this->workspace->id
        ]);

        $response = $this->actingAs($this->member)->get("/workspaces/{$this->workspace->id}/tasks?my");

        $response->assertSee($membersTask->name);
        $response->assertDontSee($otherTask->name);
    }

    /** @test */
    public function workspace_members_can_see_tasks_they_have_created()
    {
        $membersTask = factory(Task::class)->create([
            'name' => 'Go to the store.',
            'workspace_id' => $this->workspace->id,
            'created_by' => $this->member->id
        ]);

        $otherTask = factory(Task::class)->create([
            'name' => 'Clean your room.',
            'workspace_id' => $this->workspace->id
        ]);
        $response = $this->actingAs($this->member)->get("/workspaces/{$this->workspace->id}/tasks?creator={$this->member->id}");


        $response->assertSee($membersTask->name);
        $response->assertDontSee($otherTask->name);
    }

    /** @test */
    function tasks_can_be_fetched_via_api_route()
    {
        $don = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'workspace_id' => $this->workspace->id
        ]);

        $this->workspace->addMember($don);

        $this->actingAs($don)->get("/api/workspaces/{$this->workspace->id}/tasks")
            ->assertSee($task->name)
            ->assertSee($task->creator->full_name)
            ->assertSee($task->assignee->full_name)
            ->assertSee($task->status)
            ->assertSee($task->start_date)
            ->assertSee($task->finish_date);
    }
}
