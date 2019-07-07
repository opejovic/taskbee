<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTasksTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function workspace_members_can_see_all_workspace_tasks_and_their_properties()
	{
		$this->withoutExceptionHandling();
		$workspace = factory(Workspace::class)->create();
		$don = factory(User::class)->create();
		$jackie = factory(User::class)->create();
		$task = factory(Task::class)->create(['workspace_id' => $workspace->id]);

		$workspace->members()->attach($don);
		$workspace->members()->attach($jackie);


		$this->actingAs($don)->get("/workspaces/{$workspace->id}/tasks")
			->assertSee($task->name)
			->assertSee($task->creator->full_name)
			->assertSee($task->assignee->full_name)
			->assertSee($task->status)
			->assertSee($task->start_date)
			->assertSee($task->finish_date);

		$this->actingAs($jackie)->get("/workspaces/{$workspace->id}/tasks")
			->assertSee($task->name)
			->assertSee($task->creator->full_name)
			->assertSee($task->assignee->full_name)
			->assertSee($task->status)
			->assertSee($task->start_date)
			->assertSee($task->finish_date);
	}

	/** @test */
	function workspace_members_can_see_tasks_they_are_responsible_for()
	{
		$workspace = factory(Workspace::class)->create();
		$member = factory(User::class)->create();
		$workspace->members()->attach($member);

		$membersTask = factory(Task::class)->create([
			'name' => 'Go to the store.',
			'workspace_id' => $workspace->id,
			'user_responsible' => $member->id
		]);

		$otherTask = factory(Task::class)->create([
			'name' => 'Clean your room.',
			'workspace_id' => $workspace->id
		]);

		$response = $this->actingAs($member)->get("/workspaces/{$workspace->id}/tasks?my");

		$response->assertSee('Go to the store.');
		$response->assertDontSee('Clean your room.');
	}

	/** @test */
	function workspace_members_can_see_tasks_they_have_created()
	{
		$workspace = factory(Workspace::class)->create();
		$member = factory(User::class)->create();
		$workspace->members()->attach($member);

		$membersTask = factory(Task::class)->create([
			'name' => 'Go to the store.',
			'workspace_id' => $workspace->id,
			'created_by' => $member->id
		]);

		$otherTask = factory(Task::class)->create([
			'name' => 'Clean your room.',
			'workspace_id' => $workspace->id
		]);
		$response = $this->actingAs($member)->get("/workspaces/{$workspace->id}/tasks?creator={$member->id}");


		$response->assertSee('Go to the store.');
		$response->assertDontSee('Clean your room.');
	}
}
