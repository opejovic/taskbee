<?php

namespace App\Http\Controllers;

use App\Exceptions\SubscriptionExpiredException;
use App\Filters\TaskFilters;
use App\Mail\TaskCreatedEmail;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class WorkspaceTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Workspace $workspace, TaskFilters $filters)
    {
        try {
            $this->authorize('update', $workspace);
            
            $tasks = $workspace->getTasks($filters);
                
            if (request()->wantsJson()) {
                return response([$workspace, $tasks], 200);
            }

            return view('tasks.index', [
                'workspace' => $workspace,
                'tasks' => $tasks,
            ]);
        } catch (SubscriptionExpiredException $e) {
            if (Auth::user()->owns($workspace)) {
                return redirect(route('subscription-expired.show', $workspace));
            }

            return response("Subscription exipred. Please renew your subscription.", 423);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param App\Models\Workspace $workspace
     * @return \Illuminate\Http\Response
     */
    public function create(Workspace $workspace)
    {
        try {
            $this->authorize('update', $workspace);

            return view('tasks.create', [
                'workspace' => $workspace,
                'members' => $workspace->allMembers(),
            ]);    
        } catch (SubscriptionExpiredException $e) {
            return response("Subscription exipred. Please renew your subscription.", 423);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Workspace $workspace
     * @return \Illuminate\Http\Response
     */
    public function store(Workspace $workspace)
    {
        try {
            $this->authorize('update', $workspace);

            request()->validate([
                'name' => ['required', 'min:3'],
                'user_responsible' => ['required'],
                'start_date' => ['required'],
                'finish_date' => ['required'],
                'status' => ['required'],
            ]);

            $task = Task::create([
                'created_by' => Auth::user()->id,
                'workspace_id' => $workspace->id,
                'name' => request('name'),
                'user_responsible' => request('user_responsible'),
                'start_date' => request('start_date'),
                'finish_date' => request('finish_date'),
                'status' => request('status'),
            ]);

            // Temporary - excluded the email returned from the relationship. Also, passing Auth user to TaskCreatedEmail, beacuse of the excluded email..
            $assignee = User::find($task->assignee->id);
            Mail::to($assignee->email)->queue(new TaskCreatedEmail($task, Auth::user()));

            if (request()->wantsJson()) {
                return $task;
            }

            return redirect(route('tasks.index', $workspace));
        } catch (SubscriptionExpiredException $e) {
            return response("Subscription exipred. Please renew your subscription.", 423);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Workspace $workspace, Task $task)
    {
        try {
            $this->authorize('update', $workspace);

            request()->validate([
                'status' => ['required'],
            ]);

            $task->updateStatus(request('status'));

            return response(['message' => 'Task updated!'], 200);
        } catch (SubscriptionExpiredException $e) {
            return response("Subscription exipred. Please renew your subscription.", 423);
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Workspace $workspace
     * @param  App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workspace $workspace, Task $task)
    {
        try {
            $this->authorize('update', $workspace);

            $task->delete();
        } catch (SubscriptionExpiredException $e) {
            return response("Subscription exipred. Please renew your subscription.", 423);
        }
    }
}
