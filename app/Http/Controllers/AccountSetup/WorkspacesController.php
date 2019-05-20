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
     * summary
     *
     * @return void
     * @author 
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
        	'subscription_id' => $authorization->plan_id,
        ]);

        $authorization->update([
        	'workspace_id' => $workspace->id,
        ]);

        return back();
    }
}
