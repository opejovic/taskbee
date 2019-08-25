<?php

namespace taskbee\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
	/**
	 * Display the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function show()
    {
        return view('dashboards.show', [
        	'workspaces' => Auth::user()->workspacesOwned,
        ]);
    }
}
