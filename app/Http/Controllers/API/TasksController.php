<?php

namespace taskbee\Http\Controllers\API;

use taskbee\Models\Task;
use Illuminate\Http\Request;
use taskbee\Models\Workspace;
use taskbee\Http\Controllers\Controller;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \taskbee\Models\Workspace $workspace
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        return $workspace->tasks()->paginate(12);
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
     * @param  \taskbee\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \taskbee\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \taskbee\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
