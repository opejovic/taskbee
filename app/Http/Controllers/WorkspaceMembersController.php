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
        // We avoid passing in emails in our vue components, which can be visible to anyone.
        $members = $workspace->members()->select(['user_id', 'first_name', 'last_name'])->get();

    	return view('workspace-members.index', [
    		'members' => $members,
    		'plan' => Plan::whereName('Per User Monthly')->first(),
            'workspace' => $workspace,
    	]);
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Models\Workspace  $workspace
	 * @param  int $memberId
	 * @return \Illuminate\Http\Response
	 */
    public function update(Workspace $workspace, $memberId)
    {   
        abort_unless(Auth::user()->owns($workspace), 403);

        // remove the user from the workspace
        $workspace->members()->detach($memberId);
        
        $user = User::whereId($memberId)->firstOrFail();
        if ($user->workspace_id == $workspace->id) {
            $user->update(['workspace_id' => null]);
        }

        // delete the invitation for this user, also decrement the members invited from workspace and workspace authorization
        // there is a slight duplication of code here, which will have to be refactored
        $workspace->invitations->where('user_id', $memberId)->first()->delete();
        $workspace->decrement('members_invited');
        $workspace->authorization->decrement('members_invited');

        return response(['message' => 'Member removed!'], 200);
    }
}
