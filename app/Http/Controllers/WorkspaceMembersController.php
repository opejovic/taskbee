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

        $workspace->removeMember($user);

        return response(['message' => 'Member removed!'], 200);
    }
}
