<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Workspace;
use Illuminate\Http\Request;
use taskbee\Exceptions\SubscriptionExpiredException;
use taskbee\Exceptions\SubscriptionCanceledException;

class WorkspacesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

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

            return view('workspaces.show', [
                'workspace' => $workspace,
                'tasks' => $workspace->tasks,
                'invitations' => $workspace->invitations()->paginate(5)
            ]);
        } catch (SubscriptionExpiredException $e) {
            return redirect(route('subscription-expired.show', $workspace));
        } catch (SubscriptionCanceledException $e) {
            return response("You have canceled your subscription.", 423);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 
    }
}
