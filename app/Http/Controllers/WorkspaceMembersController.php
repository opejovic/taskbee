<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Workspace $workspace)
    {
    	return view('workspace-members.index', [
    		'members' => $workspace->members,
    		'plan' => Plan::whereName('Per User Monthly')->first(),
            'workspace' => $workspace,
    	]);
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function update(Workspace $workspace, $memberId)
    {   
        abort_unless(Auth::user()->owns($workspace), 403);

        // remove the user from the workspace
        $workspace->members()->detach($memberId);

        // delete the invitation for this user, also decrement the members invited from workspace and workspace authorization
        // there is a slight duplication of code here, which will have to be refactored
        $workspace->invitations->where('user_id', $memberId)->first()->delete();
        $workspace->decrement('members_invited');
        $workspace->authorization->decrement('members_invited');

        return response(['message' => 'Member removed!'], 200);
    }
}
