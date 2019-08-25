<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\User;
use taskbee\Models\Invitation;

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
        abort_if($user->isNot(auth()->user()), 403);

    	$invitation->update(['user_id' => $user->id]);
        $invitation->workspace->addMember($user);

        return redirect(route('tasks.index', $invitation->workspace->id));
    }
}
