<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceMembersController extends Controller
{
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function index(Workspace $workspace)
    {
    	return view('workspace-members.index', [
    		'members' => $workspace->allMembers(),
    		'plan' => Plan::whereName('Per User Monthly')->first(),
    	]);
    }
}
