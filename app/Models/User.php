<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * User roles.
     */
    const ADMIN = 'Admin';
    const MEMBER = 'Member';


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * summary
     *
     */
    protected $appends = ['full_name'];

    public function workspacesOwned()
    {
        return $this->hasMany(Workspace::class, 'created_by');
    }

    /**
     * Does user owns a workspace?
     *
     * @param $workspace
     * @return bool
     */
    public function owns($workspace)
    {
        return $this->id == $workspace->created_by;
    }

    /**
     * User has many tasks assigned to him.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_responsible');
    }

    /**
     * User created many tasks.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * User belongs to Workspace
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class)->with(array('members' => function($query) {
            $query->select('user_id', 'first_name', 'last_name');
        }));
    }

    /**
     * User belongs to many Workspaces.
     *
     * @return void
     */
    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_user');
    }

    /**
     * Get the users full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}"; 
    }
}
