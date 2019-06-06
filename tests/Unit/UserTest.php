<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function can_own_a_workspace()
	{
	    $user = factory(User::class)->create(['role' => User::ADMIN]);
	    $workspace = factory(Workspace::class)->create(['created_by' => $user->id]);

	    $this->assertTrue($user->owns($workspace));
	}

	/** @test */
	function can_have_many_tasks()
	{
	    $user = factory(User::class)->states('member')->create();
	    $task = factory(Task::class)->create(['user_responsible' => $user->id]);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->tasks);
	}


	/** @test */
	function can_create_many_tasks()
	{
	    $user = factory(User::class)->states('member')->create();
	    $task = factory(Task::class)->create(['created_by' => $user->id]);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->tasksCreated);
	}

	/** @test */
	function can_belong_to_a_workspace()
	{
	    $workspace = factory(Workspace::class)->create();
	    $user = factory(User::class)->states('member')->create(['workspace_id' => $workspace->id]);

	    $this->assertTrue($user->workspace->is($workspace));
	}

	/** @test */
	function can_belong_to_many_workspaces()
	{
	    $workspaceA = factory(Workspace::class)->create();
	    $workspaceB = factory(Workspace::class)->create();

	    $user = factory(User::class)->states('member')->create();

	    $user->workspaces()->attach($workspaceA);
	    $user->workspaces()->attach($workspaceB);

	    $this->assertTrue($user->workspaces->contains($workspaceA));
	    $this->assertTrue($user->workspaces->contains($workspaceB));
	}

	/** @test */
	function can_get_full_name()
	{
	    $user = factory(User::class)->create([
	    	'first_name' => 'Dane',
	    	'last_name' => 'Boghart',
	    ]);
	 
	 	$this->assertEquals('Dane Boghart', $user->full_name);   
	}
}
