<?php

namespace App\Models;

use App\Exceptions\SubscriptionExpiredException;
use App\Mail\SubscriptionExpiredEmail;
use App\Models\WorkspaceSetupAuthorization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Subscription extends Model
{
    /**
     * Class constants.
     *
     */
    const ACTIVE   = 'active';
    const UNPAID   = 'unpaid';
    const CANCELED = 'canceled';
    const PAST_DUE = 'past_due';

    /**
     * Attributes that are not mass assignable.
     *
     */
    protected $guarded = [];

    /**
     * @param $subscription
     *
     * @return mixed
     */
    private static function findByStripeId($subscription)
    {
        return self::where('stripe_id', $subscription['id'])->first();
    }

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
     * @param \Stripe\Subscription $subscription
     *
     * @param $email
     *
     * @return void
     */
    public static function buildFrom($subscription, $email)
    {
        return self::create([
            'stripe_id'  => $subscription['id'],
            'product_id' => $subscription['plan']['product'],
            'plan_id'    => $subscription['plan']['id'],
            'plan_name'  => $subscription['plan']['nickname'],
            'customer'   => $subscription['customer'],
            'email'      => $email,
            'billing'    => $subscription['billing'],
            'amount'     => $subscription['plan']['amount'] * $subscription['quantity'],
            'status'     => $subscription['status'],
            'start_date' => Carbon::createFromTimestamp($subscription['current_period_start']),
            'expires_at' => Carbon::createFromTimestamp($subscription['current_period_end']),
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
     * Change the status of the subscription.
     *
     * @param $subscription
     *
     * @return void
     */
    public static function expire($subscription)
    {
        $sub = self::findByStripeId($subscription);
        $sub->update(['status' => $subscription->status]);
        Mail::to($sub->email)->queue(new SubscriptionExpiredEmail($sub));
    }

    /**
     * Renews the subscription.
     *
     * @param $subscription
     *
     * @return void
     */
    public static function renew($subscription)
    {
        $sub = self::findByStripeId($subscription);
        $sub->update(['status' => $subscription->status]);
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
        return collect([self::UNPAID, self::PAST_DUE])->contains($this->status);
    }

    /**
     * Has subscription been canceled?
     *
     * @return bool
     */
    public function isCanceled()
    {
        return $this->status == self::CANCELED;
    }
}
