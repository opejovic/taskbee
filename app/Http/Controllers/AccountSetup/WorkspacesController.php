<?php

namespace App\Http\Controllers\AccountSetup;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspacesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $authorization = WorkspaceSetupAuthorization::findByCode(request('authorization_code'));
        
        abort_if($authorization->hasBeenUsedForWorkspace(), 403);

        $workspace = Workspace::create([
        	'name' => request('name'),
        	'created_by' => Auth::user()->id,
        	'members_invited' => $authorization->members_invited,
        	'members_limit' => $authorization->members_limit,
        	'subscription_id' => $authorization->subscription_id,
        ]);

        $workspace->members()->attach(Auth::user());
        Auth::user()->update(['workspace_id' => $workspace->id]);

        $authorization->update([
        	'admin_id' => Auth::user()->id,
            'workspace_id' => $workspace->id,
        ]);

        return back();
    }
}
