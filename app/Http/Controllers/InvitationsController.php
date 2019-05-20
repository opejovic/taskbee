<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function show($code)
    {
        $invitation = Invitation::findByCode($code);

        abort_if($invitation->hasBeenUsed(), 404);

        return view('invitations.show', ['invitation' => $invitation]);
    }
}
