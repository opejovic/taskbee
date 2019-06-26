<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function show()
    {
        return view('dashboards.show', [
        	'workspaces' => Auth::user()->workspacesOwned,
        ]);
    }
}
