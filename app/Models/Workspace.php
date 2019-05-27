<?php

namespace App\Models;

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
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function members()
    {
        return $this->hasMany(User::class);
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
}
