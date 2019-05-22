<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
 	 * Task status.
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
        return $this->belongsTo(User::class, 'user_responsible');
    }

    /**
     * Task has a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('F j, Y');
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function getFormattedFinishDateAttribute()
    {
        return $this->finish_date->format('F j, Y');
    }
}
