<?php

namespace Tests\Unit;

use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkspaceSetupAuthorizationTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function workspace_setup_authorization_can_be_retrieved_by_its_code()
	{
	    $setupAuthorization = factory(WorkspaceSetupAuthorization::class)->create(['code' => 'TESTCODE123']);
		$retrievedSetupAuthorization = WorkspaceSetupAuthorization::findByCode('TESTCODE123');
		$this->assertTrue($retrievedSetupAuthorization->is($setupAuthorization));
	}

	/** @test */
	function can_tell_if_it_has_been_used()
	{
	    $usedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'admin_id' => 1,
	    	'workspace_id' => 1,
	    	'members_invited' => 5,
	    	'members_limit' => 5,
	    	'code' => 'TESTCODE123',
	    	'subscription_id' => 345,
	    ]);

	    $unusedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'admin_id' => null,
	    	'workspace_id' => null,
	    	'members_invited' => null,
	    	'members_limit' => 5,
	    	'code' => 'TESTCODE456',
	    	'subscription_id' => 123,
	    ]);

	    $this->assertTrue($usedSetupAuthorization->hasBeenUsed());
	    $this->assertFalse($unusedSetupAuthorization->hasBeenUsed());
	    
	}

	/** @test */
	function can_tell_if_it_has_been_used_for_admin_creation()
	{
	    $usedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'admin_id' => 1,
	    	'code' => 'TESTCODE123',
	    	'subscription_id' => 345,
	    ]);

	    $unusedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'admin_id' => null,
	    	'code' => 'TESTCODE456',
	    	'subscription_id' => 123,
	    ]);

	    $this->assertTrue($usedSetupAuthorization->hasBeenUsedForAdmin());
	    $this->assertFalse($unusedSetupAuthorization->hasBeenUsedForAdmin());
	}

	/** @test */
	function can_tell_if_it_has_been_used_for_workspace_creation()
	{
	    $usedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'workspace_id' => 1,
	    	'code' => 'TESTCODE123',
	    	'subscription_id' => 345,
	    ]);

	    $unusedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'workspace_id' => null,
	    	'code' => 'TESTCODE456',
	    	'subscription_id' => 123,
	    ]);

	    $this->assertTrue($usedSetupAuthorization->hasBeenUsedForWorkspace());
	    $this->assertFalse($unusedSetupAuthorization->hasBeenUsedForWorkspace());
	}

	/** @test */
	function can_tell_if_it_has_been_used_for_member_invitations()
	{
	    $usedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'members_invited' => 5,
	    	'members_limit' => 5,
	    	'code' => 'TESTCODE123',
	    	'subscription_id' => 345,
	    ]);

	    $unusedSetupAuthorization = factory(WorkspaceSetupAuthorization::class)->create([
	    	'members_invited' => null,
	    	'members_limit' => 5,
	    	'code' => 'TESTCODE456',
	    	'subscription_id' => 123,
	    ]);

	    $this->assertTrue($usedSetupAuthorization->hasBeenUsedForMemberInvites());
	    $this->assertFalse($unusedSetupAuthorization->hasBeenUsedForMemberInvites());
	}
}
