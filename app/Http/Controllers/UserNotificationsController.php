<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\User;

class UserNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \taskbee\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return auth()->user()->notifications()->take(10)->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \taskbee\Models\User $user
     * @param int $notificationId
     * @return void
     */
    public function destroy(User $user, $notificationId)
    {
        auth()->user()->notifications()->findOrFail($notificationId)->delete();
    }
}
