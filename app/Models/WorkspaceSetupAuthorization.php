<?php

namespace App\Models;

use App\Mail\SubscriptionPurchasedEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class WorkspaceSetupAuthorization extends Model
{
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
}
