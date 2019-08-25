<?php

namespace taskbee\Models;

use taskbee\Notifications\TaskUpdated;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Task statuses.
     */
    const PENDING     = 'Pending';
    const URGENT      = 'Urgent';
    const COMPLETED   = 'Completed';

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
     * Attributes that are included in every query.
     *
     * @var array
     **/
    protected $appends = ['shortName'];

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
     * @param \taskbee\Filters\TaskFilters $filters
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

        $this->workspace->notifyMembers($this, TaskUpdated::class);
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

    /**
     * Return the shorter task name.
     *
     * @return string
     */
    public function getShortNameAttribute()
    {
        return str_limit($this->name, 15);
    }
}
