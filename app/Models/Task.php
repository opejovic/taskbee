<?php

namespace App\Models;

use App\Notifications\TaskUpdated;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
 	 * Class constants. Alowed statuses for the model.
 	 */
    const PLANNED = 'Planned';
    const IN_PROGRESS = 'In progress';
    const WAITING = 'Waiting';
    const TESTING = 'Testing';
    const DONE = 'Done';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
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
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Update the tasks status and notify the users.
     *
     */
    public function updateStatus($status)
    {
        $this->update(['status' => $status]);

        $this->workspace->members->each(function ($member) {
            $member->notify(new TaskUpdated($this, auth()->user()));
        });
    }

    /**
     * Query scope, uses ThreadFilter $filters, if provided.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param \App\Filters\TaskFilters $filters
     * @return $query
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Get formatted start date.
     *
     * @return date
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('F j, Y');
    }

    /**
     * Get formatted finish date.
     *
     * @return date
     */
    public function getFormattedFinishDateAttribute()
    {
        return $this->finish_date->format('F j, Y');
    }
}
