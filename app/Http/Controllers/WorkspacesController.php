<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\SubscriptionExpiredException;
use App\Exceptions\SubscriptionCanceledException;

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Workspace $workspace)
    {
        try {
            $this->authorize('update', $workspace);

            $tasks = $workspace->tasks->groupBy('status');

            return view('workspaces.show', [
                'workspace' => $workspace,
                'tasks' => $tasks,
            ]);    
        } catch (SubscriptionExpiredException $e) {
            return redirect(route('subscription-expired.show', $workspace));
            // return response("Subscription exipred. Please renew your subscription.", 423);
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
