<?php

namespace Tests\Unit;

use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function workspace_can_have_members()
	{
	    $workspace = factory(Workspace::class)->create();

	    $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $workspace->members);
	}
}
