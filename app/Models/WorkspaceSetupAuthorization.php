<?php

namespace App\Models;

use App\Mail\SubscriptionPurchasedEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class WorkspaceSetupAuthorization extends Model
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'code';
    }

    /**
     * Attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Sends an email to the customer that purchsed a subscription.
     *
     * @param App\Models\Subscription $subscription
     */
    public function send($subscription)
    {
        Mail::to($subscription->email)->queue(new SubscriptionPurchasedEmail($subscription, $this));
    }

    /**
     * Get the WorkspaceSetupAuthorization by its code.
     *
     * @param string $code 
     * @return App\Models\WorkspaceSetupAuthorization
     */
    public static function findByCode($code)
    {
        return self::whereCode($code)->first();
    }
    
    /**
     * Has the workspace setup authorization been used?
     *
     * @return bool
     */
    public function hasBeenUsed()
    {
        return $this->admin_id !== null 
            && $this->workspace_id !== null 
            && $this->members_invited >= $this->members_limit;
    }

    /**
     * Has the workspace setup authorization been used for admin creation?
     *
     * @return bool
     */
    public function hasBeenUsedForAdmin()
    {
        return $this->admin_id !== null;
    }

    /**
     * Has the workspace setup authorization been used for workspace creation?
     *
     * @return bool
     */
    public function hasBeenUsedForWorkspace()
    {
        return $this->workspace_id !== null;
    }

    /**
     * Has the workspace setup authorization been used for inviting members?
     *
     * @return bool
     */
    public function hasBeenUsedForMemberInvites()
    {
        return $this->members_invited >= $this->members_limit;
    }

}
