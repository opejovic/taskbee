<?php

namespace App\Http\Controllers\AccountSetup;

use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InitialSetupController extends Controller
{
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function show(WorkspaceSetupAuthorization $authorization)
    {
    	abort_if($authorization->hasBeenUsed(), 404);


        if (! $authorization->hasBeenUsedForWorkspace()) {
            return view('workspace-setup.create-workspace', ['authorization' => $authorization]);
        }

        if ($authorization->hasBeenUsedForWorkspace() && ! $authorization->hasBeenUsedForMemberInvites()) {
            return view('workspace-setup.invite-members', ['authorization' => $authorization]);
        } 

        // if ($authorization->hasBeenUsedForMemberInvites()) {
        //     return response(['Okay, you are all done!', 200]);
        // }

    	return view('workspace-setup.show');
    }
}
