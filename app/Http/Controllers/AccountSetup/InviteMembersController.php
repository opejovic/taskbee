<?php

namespace App\Http\Controllers\AccountSetup;

use App\Facades\InvitationCode;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Http\Request;

class InviteMembersController extends Controller
{
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function store(Workspace $workspace)
    {
        $authorization = WorkspaceSetupAuthorization::findByCode(request('authorization_code'));

        abort_if($authorization->hasBeenUsedForMemberInvites(), 403);

       // abort_unless(Auth::user()->owns($workspace), 403);

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
