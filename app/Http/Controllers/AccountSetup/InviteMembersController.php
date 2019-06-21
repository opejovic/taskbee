<?php

namespace App\Http\Controllers\AccountSetup;

use App\Facades\InvitationCode;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InviteMembersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Models\Workspace $workspace
     * @return \Illuminate\Http\Response
     */
    public function store(Workspace $workspace)
    {
        $authorization = WorkspaceSetupAuthorization::findByCode(request('authorization_code'));

        abort_if($authorization->hasBeenUsedForMemberInvites(), 403);

        request()->validate([
            'first_name' => ['required', 'alpha', 'min:2'],
            'last_name' => ['required', 'alpha', 'min:2'],
            'email' => [
                'required', 
                'email',
                // Same user cant be invited to the workspace twice
                Rule::unique('invitations')->where(function ($query) use ($workspace) {
                    return ! $query->where('workspace_id', $workspace->id);
                })
            ],
        ]);

    	$invitation = Invitation::create([
    		'first_name' => request('first_name'),
    		'last_name' => request('last_name'),
    		'email' => request('email'),
            'user_role' => User::MEMBER,
    		'code'  => InvitationCode::generate(),
    		'workspace_id' => $workspace->id,
    		'subscription_id' => $workspace->subscription_id
    	])->send();

        $workspace->increment('members_invited');
        $authorization->increment('members_invited');

        if ($authorization->hasBeenUsedForMemberInvites()) {
            return redirect(route('workspaces.show', $workspace));
        }

        return back();
    }
}
