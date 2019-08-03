<?php

namespace App\Models;

use App\Mail\SlotPurchasedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'workspace_user');
    }

    /**
     * Workspace has many tasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class)->with('creator', 'assignee');
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
     * Workspace has one WorkspaceSetupAuthorization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function authorization()
    {
        return $this->hasOne(WorkspaceSetupAuthorization::class);
    }

    /**
     * Workspace has many Invitations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Increments members limit number by 1.
     *
     * @param $subscription
     *
     * @return void
     */
    public static function addSlot($subscription)
    {
        $workspace = self::where('subscription_id', $subscription)->first();
        $workspace->increment('members_limit');
        
        // Is this really needed? Perhaps, after adding slot, we fire an event -> and notify the user he can now invite additional team member.
        $authorization = WorkspaceSetupAuthorization::where('subscription_id', $subscription)->first();
        $authorization->increment('members_limit');
        
        self::notifyOwner($workspace, $authorization);
    }

    /**
     * Notify the workspace owner about the newly added member slot.
     *
     * @param $workspace
     * @return void
     **/
    public static function notifyOwner($workspace, $authorization)
    {
        Mail::to($workspace->creator->email)->queue(new SlotPurchasedEmail($workspace, $authorization));
    }

    /**
     * Add a member to the workspace.
     *
     * @param $member
     *
     * @return void
     */
    public function addMember($member)
    {
        $this->members()->attach($member);
    }

    /**
     * Get the tasks for the workspace, applying the filters
     * if there are any, and sort them by status name, and by latest.
     *
     * @param $filters
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTasks($filters)
    {
        return $this->tasks()
            ->filter($filters)
            ->orderBy('status', 'asc')
            ->latest()
            ->get()
            ->groupBy('status');
    }
}
