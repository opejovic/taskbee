<?php

namespace App\Http\Controllers\AccountSetup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkspaceSetupAuthorization;

class InitialSetupController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param \App\Models\WorkspaceSetupAuthorization $authorization
     *
     * @return \Illuminate\Http\Response
     */
    public function show(WorkspaceSetupAuthorization $authorization)
    {
        abort_if($authorization->hasBeenUsed(), 404);


        if (!$authorization->hasBeenUsedForWorkspace()) {
            return view('workspace-setup.create-workspace', [
                'authorization' => $authorization
            ]);
        }

        if ($authorization->hasBeenUsedForWorkspace() && !$authorization->hasBeenUsedForMemberInvites()) {
            return view('workspace-setup.invite-members', [
                'authorization' => $authorization
            ]);
        }

        return view('workspace-setup.show');
    }
}
