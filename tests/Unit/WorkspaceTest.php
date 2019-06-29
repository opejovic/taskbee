<?php

namespace Tests\Unit;

use App\Models\Subscription;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
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
	    $workspace->members()->attach($creator);
	    $memberOne = factory(User::class)->create();
	    $memberTwo = factory(User::class)->create();

	    $workspace->members()->attach($memberOne);
	    $workspace->members()->attach($memberTwo);

	    $this->assertEquals(3, $workspace->members->count());
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

	/** @test */
	function it_has_a_subscription()
	{
		$subscription = factory(Subscription::class)->create();
	    $workspace = factory(Workspace::class)->create(['subscription_id' => $subscription->stripe_id]);

	    $this->assertTrue($workspace->subscription->is($subscription));
	}

	/** @test */
	function it_can_add_a_member()
	{
	    $workspace = factory(Workspace::class)->create();
	    $member = factory(User::class)->create();
	    $this->assertCount(0, $workspace->members);

	    $workspace->addMember($member);
	    $this->assertCount(1, $workspace->fresh()->members);
	    $this->assertTrue($workspace->fresh()->members->pluck('user_id')->contains($member->id));
	}

	/** @test */
	function it_can_have_many_invitations()
	{
	    $workspace = factory(Workspace::class)->create();

	    $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $workspace->invitations);
	}

	/** @test */
	function it_has_an_authorization()
	{
	    $workspace = factory(Workspace::class)->create();
	    $authorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'workspace_id' => $workspace->id,
	    ]);

	    $this->assertEquals($authorization->id, $workspace->authorization->id);
	}
}
