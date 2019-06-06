<?php

namespace App\Models;

use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Workspace extends Model
{
    /**
     * Attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Workspace belongs to Creator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all workspace members including the workspace creator.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'workspace_user');
    }

    /**
     * Get all workspace members including the workspace creator.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function allMembers()
    {
        return $this->members->push($this->creator)->unique()->sortBy('first_name');
    }

    /**
     * Workspace has many tasks.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Workspace belongs to Subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'stripe_id');
    }

    /**
     * Increments members limit number by 1.
     *
     * @return void
     */
    public static function addSlot($subscription)
    {
        self::where('subscription_id', $subscription)->first()->increment('members_limit');

        // Is this really needed? Perhaps, after adding slot, we fire an event -> and notify the user he can now invite additional team member.
        $authorization = WorkspaceSetupAuthorization::where('subscription_id', $subscription)->first();
        $authorization->increment('members_limit');
    }

    /**
     * Add a member to the workspace.
     *
     * @return void
     * @author 
     */
    public function addMember($member)
    {
        $this->members()->attach($member);
    }
}
