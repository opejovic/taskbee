<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\User;

class ClearAllNotificationsController extends Controller
{
    /**
     * Delete all notifications for the authenticated user.
     *
     * @param \taskbee\Models\User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        auth()->user()->notifications()->delete();

        return response('Notifications cleared.', 200);
    }
}
