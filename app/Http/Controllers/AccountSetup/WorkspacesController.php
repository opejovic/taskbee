<?php

namespace App\Http\Controllers\AccountSetup;

use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkspaceSetupAuthorization;

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

        request()->validate([
            'name' => ['required', 'min:3', 'unique:workspaces,name']
        ]);

        $workspace = Workspace::create([
            'name'            => request('name'),
            'created_by'      => Auth::user()->id,
            'members_invited' => $authorization->members_invited,
            'members_limit'   => $authorization->members_limit,
            'subscription_id' => $authorization->subscription_id,
        ]);

        $workspace->members()->attach(Auth::user());
        Auth::user()->update(['workspace_id' => $workspace->id]);

        $authorization->update([
            'admin_id'     => Auth::user()->id,
            'workspace_id' => $workspace->id,
        ]);

        return back();
    }
}
