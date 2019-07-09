<?php

namespace App\Models;

use App\Mail\InvitationEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Invitation extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Send the invitation email.
     *
     * @return void
     */
    public function send()
    {
        Mail::to($this->email)->queue(new InvitationEmail($this));
    }

    /**
     * Invitation belongs to Workspace
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Retrieve an invitation by its code.
     *
     * @param $code
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findByCode($code)
    {
        return self::where('code', $code)->firstOrFail();
    }

    /**
     * Has the invitation been used?
     *
     * @return bool
     */
    public function hasBeenUsed()
    {
        return $this->user_id !== null;
    }

    /**
     * Return the full name of the invited user.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
