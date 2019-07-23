<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return auth()->user()->notifications->take(10);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @param int $notificationId
     *
     * @return void
     */
    public function destroy(User $user, $notificationId)
    {
        auth()->user()->notifications()->findOrFail($notificationId)->delete();
    }
}
