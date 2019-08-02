<?php

namespace App\Models;

use App\Notifications\TaskUpdated;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Class constants. Allowed statuses for the model.
     */
    const PLANNED     = 'Planned';
    const IN_PROGRESS = 'In progress';
    const WAITING     = 'Waiting';
    const TESTING     = 'Testing';
    const DONE        = 'Done';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'finish_date'];

    /**
     * Task has an assignee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_responsible')->select(['id', 'first_name', 'last_name']);
    }

    /**
     * Task has a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'first_name', 'last_name']);
    }

    /**
     * Task belongs to a Workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Query scope, uses ThreadFilter $filters, if provided.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param \App\Filters\TaskFilters $filters
     *
     * @return void
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Update the tasks status and notify the users.
     *
     * @param $status
     */
    public function updateStatus($status)
    {
        $this->update(['status' => $status]);

        $this->notifyMembers();
    }

    /**
     * Notify the task workspace members that the task status has been changed.
     *
     * @return void
     **/
    public function notifyMembers()
    {
        $this->workspace->members->each->notify(new TaskUpdated($this, auth()->user()));
    }

    /**
     * Was task updated less than a minute ago?
     *
     * @return boolean
     */
    public function wasUpdatedRecently()
    {
        return $this->updated_at->gt(now()->subMinute());
    }

    /**
     * Get formatted start date.
     *
     * @return string
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('F j, Y');
    }

    /**
     * Get formatted finish date.
     *
     * @return string
     */
    public function getFormattedFinishDateAttribute()
    {
        return $this->finish_date->format('F j, Y');
    }
}
