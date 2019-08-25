<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Workspace;
use Illuminate\Http\Request;
use taskbee\Exceptions\SubscriptionExpiredException;
use taskbee\Exceptions\SubscriptionCanceledException;

class WorkspacesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param \taskbee\Models\Workspace $workspace
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Workspace $workspace)
    {
        try {
            $this->authorize('update', $workspace);

            $workspace = $workspace->load(
                [
                    'creator' => function ($query) {
                        $query->select('id', 'first_name', 'last_name');
                    },
                    'authorization'
                ]
            );

            return view('workspaces.show', [
                'workspace' => $workspace,
                'tasks' => $workspace->tasks->take(12),
                'invitations' => $workspace->invitations,
            ]);
            
        } catch (SubscriptionExpiredException $e) {
            return redirect(route('subscription-expired.show', $workspace));
        } catch (SubscriptionCanceledException $e) {
            return response("You have canceled your subscription.", 423);
        }
    }
}
