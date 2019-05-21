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
}
