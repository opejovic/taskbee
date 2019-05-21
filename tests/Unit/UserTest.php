<?php

namespace Tests\Unit;

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
}
