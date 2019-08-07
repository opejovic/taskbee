<?php

namespace taskbee\Models;

use taskbee\Facades\AuthorizationCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use taskbee\Mail\SubscriptionPurchasedEmail;

class WorkspaceSetupAuthorization extends Model
{
    /**
     * Class constants.
     */
    const INITIAL_MEMBER_COUNT = 1;

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
     * Authorization has one subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'stripe_id', 'subscription_id');
    }

    /**
     * Create an authorization for subscription.
     *
     * @param \taskbee\Models\Subscription $subscription
     *
     * @return void
     */
    public static function authorize($subscription)
    {
        self::create([
            'email'           => $subscription->email,
            'customer'        => $subscription->customer,
            'members_invited' => self::INITIAL_MEMBER_COUNT,
            'code'            => AuthorizationCode::generate(),
            'subscription_id' => $subscription->stripe_id,
            'plan_id'         => $subscription->plan_id,
            'members_limit'   => $subscription->plan->members_limit,
        ])->send();
    }

    /**
     * Sends an email to the customer that purchased a subscription.
     */
    public function send()
    {
        Mail::to($this->email)->queue(new SubscriptionPurchasedEmail($this));
    }

    /**
     * Get the WorkspaceSetupAuthorization by its code.
     *
     * @param string $code
     *
     * @return \taskbee\Models\WorkspaceSetupAuthorization
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

    /**
     * How many invites remain for this authorization.
     *
     * @return integer
     */
    public function getInvitesRemainingAttribute()
    {
        return $this->members_limit - $this->members_invited;
    }
}
