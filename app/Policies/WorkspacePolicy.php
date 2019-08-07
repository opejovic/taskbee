<?php

namespace taskbee\Policies;

use taskbee\Exceptions\SubscriptionCanceledException;
use taskbee\Exceptions\SubscriptionExpiredException;
use taskbee\Models\User;
use taskbee\Models\Workspace;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkspacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the workspace.
     *
     * @param  \taskbee\Models\User  $user
     * @param  \taskbee\Models\Workspace  $workspace
     * @return mixed
     */
    public function view(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can create workspaces.
     *
     * @param  \taskbee\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the workspace.
     *
     * @param \taskbee\Models\User $user
     * @param \taskbee\Models\Workspace $workspace
     *
     * @return mixed
     * @throws \taskbee\Exceptions\SubscriptionCanceledException
     * @throws \taskbee\Exceptions\SubscriptionExpiredException
     */
    public function update(User $user, Workspace $workspace)
    {
        if ($workspace->members->contains($user)) {

            if ($workspace->subscription->isExpired()) {
                throw new SubscriptionExpiredException;
            }

            if ($workspace->subscription->isCanceled()) {
                throw new SubscriptionCanceledException;
            }

            return true;
        }
    }

    /**
     * Determine whether the user can delete the workspace.
     *
     * @param  \taskbee\Models\User  $user
     * @param  \taskbee\Models\Workspace  $workspace
     * @return mixed
     */
    public function delete(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can restore the workspace.
     *
     * @param  \taskbee\Models\User  $user
     * @param  \taskbee\Models\Workspace  $workspace
     * @return mixed
     */
    public function restore(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the workspace.
     *
     * @param  \taskbee\Models\User  $user
     * @param  \taskbee\Models\Workspace  $workspace
     * @return mixed
     */
    public function forceDelete(User $user, Workspace $workspace)
    {
        //
    }
}
