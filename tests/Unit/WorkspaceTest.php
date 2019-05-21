<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function it_has_a_creator()
	{
	    $user = factory(User::class)->states('admin')->create();
	    $workspace = factory(Workspace::class)->create(['created_by' => $user->id]);

	    $this->assertTrue($workspace->creator->is($user));
	    
	}

	/** @test */
	function it_can_have_members()
	{
	    $workspace = factory(Workspace::class)->create();

	    $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $workspace->members);
	}

	/** @test */
	function it_can_retrieve_all_of_its_members_including_the_creator()
	{
	    $creator = factory(User::class)->states('admin')->create();
	    $workspace = factory(Workspace::class)->create(['created_by' => $creator->id]);
	    $memberOne = factory(User::class)->states('member')->create(['workspace_id' => $workspace->id]);
	    $memberTwo = factory(User::class)->states('member')->create(['workspace_id' => $workspace->id]);

	    $this->assertEquals(3, $workspace->allMembers()->count());
	}

	/** @test */
	function it_can_have_tasks()
	{
	    $workspace = factory(Workspace::class)->create();
	    $taskOne = factory(Task::class)->create(['workspace_id' => $workspace->id]);
	    $taskTwo = factory(Task::class)->create(['workspace_id' => $workspace->id]);		
	    $otherWorkspaceTask = factory(Task::class)->create(['workspace_id' => 999]);		

	    $this->assertTrue($workspace->tasks->contains($taskOne));
	    $this->assertTrue($workspace->tasks->contains($taskTwo));
	    $this->assertFalse($workspace->tasks->contains($otherWorkspaceTask));
	}
}
