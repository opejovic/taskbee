<?php

namespace taskbee\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use taskbee\Models\WorkspaceSetupAuthorization;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        # If authenticated user has subscribed, but has not yet created a workspace, he is redirected to workspace creation page.
        if ($authorization = WorkspaceSetupAuthorization::whereNull('admin_id')->whereEmail(Auth::user()->email)->first()) {
            return redirect(route('workspace-setup.show', $authorization));
        };

        return view('home');
    }
}
