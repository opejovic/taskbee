<?php

namespace App\Models;

use App\Exceptions\SubscriptionExpiredException;
use App\Models\WorkspaceSetupAuthorization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * Attributes that are not mass assignable.
     *
     */
    protected $guarded = [];
    
    /**
     * Subscription has an owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Get the invitation associated with the subscription.
     */
    public function invitation()
    {
        return $this->hasOne(Invitation::class, 'subscription_id');
    }

    /**
     * Create a subscription from StripeSubscription.
     *
     * @param Stripe\Subscription $subscription
     * @return void
     */
    public static function buildFrom($subscription, $email)
    {
        return self::create([
            'stripe_id'   => $subscription['id'],
            'product_id'  => $subscription['items']['data'][1]['plan']['product'],
            'plan_id'     => $subscription['items']['data'][1]['plan']['id'],
            'plan_name'   => $subscription['items']['data'][1]['plan']['nickname'],
            'customer'    => $subscription['customer'],
            'email'       => $email,
            'billing'     => $subscription['billing'],
            'amount'      => $subscription['items']['data'][1]['plan']['amount'] * $subscription['items']['data'][1]['quantity'],
            'status'      => $subscription['status'],
            'start_date'  => Carbon::createFromTimestamp($subscription['current_period_start']),
            'expires_at'  => Carbon::createFromTimestamp($subscription['current_period_end']),
        ])->getAuthorization();
    }

    /**
     * Get authorization for the subscription.
     *
     * @return void
     */
    public function getAuthorization()
    {
        WorkspaceSetupAuthorization::authorize($this);
    }

    /**
     * Cast subscription to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'email' => $this->email,
            'bundle_name' => $this->bundle_name,
            'amount' => $this->amount,
            'status' => $this->status,
            'start_date' => $this->start_date->format('Y-m-d'),
            'expires_at' => $this->expires_at->format('Y-m-d'),
        ];
    }

    /**
     * Subscription belongs to a Plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
       return $this->belongsTo(Plan::class, 'plan_id', 'stripe_id');
    }

    /**
     * Has subscription expired?
     *
     * @return bool
     */
    public function isExpired()
    {
        return now()->greaterThan($this->expires_at);
    }
}
