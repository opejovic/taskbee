<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;
use App\Exceptions\SubscriptionExpiredException;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkspacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the workspace.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return mixed
     */
    public function view(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can create workspaces.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the workspace.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return mixed
     */
    public function update(User $user, Workspace $workspace)
    {
        if ($workspace->allMembers()->contains($user)) {

            if ($workspace->subscription->isExpired()) {
                throw new SubscriptionExpiredException;
            }

            return true;
        }
    }

    /**
     * Determine whether the user can delete the workspace.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return mixed
     */
    public function delete(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can restore the workspace.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return mixed
     */
    public function restore(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the workspace.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return mixed
     */
    public function forceDelete(User $user, Workspace $workspace)
    {
        //
    }
}
