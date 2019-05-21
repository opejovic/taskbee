<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceTasksController extends Controller
{
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function create(Workspace $workspace)
    {
        abort_unless($workspace->allMembers()->contains(Auth::user()), 403);

        return view('tasks.create', [
            'workspace' => $workspace,
            'members' => $workspace->allMembers(),
        ]);
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function store(Workspace $workspace)
    {
        $task = Task::create([
			'created_by' => Auth::user()->id,
            'workspace_id' => $workspace->id,
			'name' => request('name'),
			'user_responsible' => request('user_responsible'),
			'start_date' => request('start_date'),
			'finish_date' => request('finish_date'),
			'status' => request('status'),
        ]);

        if (request()->wantsJson()) {
            return response([], 201);
        }

        return redirect(route('workspaces.show', $workspace));
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function destroy(Workspace $workspace, Task $task)
    {
        $task->delete();
    }
}
