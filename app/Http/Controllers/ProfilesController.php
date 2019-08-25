<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\User;

class ProfilesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \taskbee\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('profiles.show', ['profileUser' => $user]);
    }
}
