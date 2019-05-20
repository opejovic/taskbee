<?php

namespace Tests\Unit;

use App\Models\Invitation;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function it_belongs_to_a_workspace()
	{
		$workspace = factory(Workspace::class)->create();
	    $invitation = factory(Invitation::class)->create(['workspace_id' => $workspace->id]);

	    $this->assertInstanceOf('App\Models\Workspace', $invitation->workspace);
	}
}
