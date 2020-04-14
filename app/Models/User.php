<?php

namespace taskbee\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * User roles.
     */
    const ADMIN  = 'Admin';
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
     * Attributes to include in every query.
     *
     * @var array
     *
     */
    protected $appends = ['full_name'];

    /**
     * User can own many workspaces.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workspacesOwned()
    {
        return $this->hasMany(Workspace::class, 'created_by');
    }

    /**
     * Does user owns a workspace?
     *
     * @param \taskbee\Models\Workspace $workspace
     * @return bool
     */
    public function owns($workspace)
    {
        return $this->id === $workspace->created_by;
    }

    /**
     * Users have many tasks assigned to them.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_responsible');
    }

    /**
     * User created many tasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * User belongs to a Workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class)->with([
            'members' => function ($query) {
                $query->select('user_id', 'first_name', 'last_name');
            }
        ]);
    }

    /**
     * Retrieve the workspace authenticated user belongs to, if there is one.
     *
     * @return \taskbee\Models\Workspace
     **/
    public function workspaceOwned()
    {
        # Consider implementing Null Object
        return $this->workspace()->exists() ? $this->workspace : 'NO_WORKSPACE_CREATED';
    }

    /**
     * User belongs to many Workspaces.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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

    /**
     * Get the avatar path attribute, if there is one. Otherwise, return the default avatar path.
     *
     * @return string
     */
    public function getAvatarPathAttribute($avatar)
    {
        return $avatar ? asset("storage/{$avatar}") : asset('img/default.png');
    }
}
