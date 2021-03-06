<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Invitation;

class InvitationsController extends Controller
{
    /**
     * Display the invitation.
     *
     * @param  string $code
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $invitation = Invitation::findByCode($code);

        abort_if($invitation->hasBeenUsed(), 404);

        return view('invitations.show', ['invitation' => $invitation]);
    }
}
