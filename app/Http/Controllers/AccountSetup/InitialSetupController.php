<?php

namespace taskbee\Http\Controllers\AccountSetup;

use taskbee\Http\Controllers\Controller;
use taskbee\Models\WorkspaceSetupAuthorization;

class InitialSetupController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param \taskbee\Models\WorkspaceSetupAuthorization $authorization
     *
     * @return \Illuminate\View\View
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

        return home();
    }
}
