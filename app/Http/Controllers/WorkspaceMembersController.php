<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\User;
use taskbee\Models\Workspace;
use Illuminate\Support\Facades\Auth;

class WorkspaceMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \taskbee\Models\Workspace $workspace
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Workspace $workspace)
    {
		$this->authorize('update', $workspace);

        # We avoid passing in emails in our vue components, which can be visible to anyone.
        $members = $workspace->members()->select(['user_id', 'first_name', 'last_name'])->get();

        return view('workspace-members.index', [
            'members'   => $members,
            'workspace' => $workspace,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \taskbee\Models\Workspace $workspace
     * @param int $memberId
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Workspace $workspace, $memberId)
    {
        abort_unless(Auth::user()->owns($workspace), 403);

        $user = User::whereId($memberId)->firstOrFail();
        abort_if($user->owns($workspace), 403);

        # Remove the user from the workspace, if the user isn't the workspace owner
        if ($user->workspace_id == $workspace->id) {
            $user->update(['workspace_id' => null]);
        }

        # Delete the invitation for this user, also decrement the members invited from workspace and workspace authorization
        # there is a slight duplication of code here, which will have to be refactored
        $workspace->members()->detach($memberId);
        $workspace->invitations->where('user_id', $memberId)->first()->delete();
        $workspace->decrement('members_invited');
        $workspace->authorization->decrement('members_invited');

        return response(['message' => 'Member removed!'], 200);
    }
}
