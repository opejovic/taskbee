<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;

class AcceptInvitationsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $invitation = Invitation::findByCode(request('invitation_code'));

        abort_if($invitation->hasBeenUsed(), 403);
        
        $user = User::where('email', $invitation->email)->first();

        if (auth()->user()->email !== $user->email) {
			abort(403);        	
        }

    	$invitation->update(['user_id' => $user->id]);

        // add a member to workspace
        $invitation->workspace->addMember($user);
		return redirect(route('tasks.index', $invitation->workspace->id));
    }
}
