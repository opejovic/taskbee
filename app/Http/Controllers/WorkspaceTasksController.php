<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Task;
use taskbee\Models\User;
use taskbee\Models\Workspace;
use taskbee\Filters\TaskFilters;
use Illuminate\Http\Request;
use taskbee\Mail\TaskCreatedEmail;
use taskbee\Notifications\TaskCreated;
use taskbee\Notifications\TaskDeleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use taskbee\Exceptions\SubscriptionExpiredException;

class WorkspaceTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
                'tasks'     => $tasks,
            ]);
        } catch (SubscriptionExpiredException $e) {
            if (Auth::user()->owns($workspace)) {
                return redirect(route('subscription-expired.show', $workspace));
            }

            return response("Subscription expired. Please renew your subscription.", 423);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \taskbee\Models\Workspace $workspace
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Workspace $workspace)
    {
        try {
            $this->authorize('update', $workspace);

            return view('tasks.create', [
                'workspace' => $workspace,
                'members'   => $workspace->allMembers(),
            ]);
        } catch (SubscriptionExpiredException $e) {
            return response("Subscription expired. Please renew your subscription.", 423);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \taskbee\Models\Workspace $workspace
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Workspace $workspace)
    {
        try {
            $this->authorize('update', $workspace);

            request()->validate([
                'name'             => ['required', 'min:3', 'not_regex:/(.)\\1{4,}/'],
                'user_responsible' => ['required'],
                'start_date'       => ['required'],
                'finish_date'      => ['required'],
                'status'           => ['required'],
            ]);

            $task = Task::create([
                'created_by'       => Auth::user()->id,
                'workspace_id'     => $workspace->id,
                'name'             => request('name'),
                'user_responsible' => request('user_responsible'),
                'start_date'       => request('start_date'),
                'finish_date'      => request('finish_date'),
                'status'           => request('status'),
            ]);

            $assignee = User::find($task->assignee->id);
            Mail::to($assignee->email)->queue(new TaskCreatedEmail($task, Auth::user()));

            $this->notifyUsers($task, TaskCreated::class);

            if (request()->wantsJson()) {
                return $task;
            }

            return redirect(route('tasks.index', $workspace));
        } catch (SubscriptionExpiredException $e) {
            return response("Subscription expired. Please renew your subscription.", 423);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \taskbee\Models\Workspace $workspace
     * @param \taskbee\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Workspace $workspace, Task $task)
    {
        try {
            $this->authorize('update', $workspace);

            if ($task->wasUpdatedRecently()) {
                return response(['message' => 'Task may be updated only once per minute.'], 429);
            }

            request()->validate([
                'status' => ['required'],
            ]);

            $task->updateStatus(request('status'));

            return response(['message' => 'Task updated!'], 200);
        } catch (SubscriptionExpiredException $e) {
            return response("Subscription expired. Please renew your subscription.", 423);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \taskbee\Models\Workspace $workspace
     * @param \taskbee\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Workspace $workspace, Task $task)
    {
        try {
            $this->authorize('update', $workspace);

            $this->notifyUsers($task, TaskDeleted::class);

            $task->delete();

        } catch (SubscriptionExpiredException $e) {
            return response("Subscription expired. Please renew your subscription.", 423);
        }
    }

    /**
     * Notify the members of workspace for the given task, about changes that occurred.
     *
     * @param \taskbee\Models\Task $task
     * @param $class
     *
     * @return void
     **/
    private function notifyUsers($task, $class)
    {
        $task->workspace->members->each->notify(new $class($task, auth()->user()));
    }
}
