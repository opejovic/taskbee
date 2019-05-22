<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function it_has_an_assignee()
	{
		$user = factory(User::class)->create();
	    $task = factory(Task::class)->create(['user_responsible' => $user->id]);

	    $this->assertTrue($task->assignee->is($user));
	}

	/** @test */
	function it_has_a_creator()
	{
		$user = factory(User::class)->create();
	    $task = factory(Task::class)->create(['created_by' => $user->id]);

	    $this->assertTrue($task->creator->is($user));
	}

	/** @test */
	function it_can_get_a_formatted_start_date()
	{
	    $task = factory(Task::class)->create(['start_date' => '2019-05-22']);

	    $this->assertEquals('May 22, 2019', $task->formatted_start_date);
	    
	}

	/** @test */
	function it_can_get_a_formatted_finish_date()
	{
	    $task = factory(Task::class)->create(['finish_date' => '2019-05-22']);

	    $this->assertEquals('May 22, 2019', $task->formatted_finish_date);
	    
	}
}
