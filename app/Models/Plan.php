<?php

namespace App\Models;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = [];

    /**
     * Plan belongs to a Bundle.
     *
     * @return App\Models\Bundle
     */
    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'product', 'stripe_id');
    }

    /**
     * Plan has many subscriptions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id', 'stripe_id');
    }

    /**
     * Purchase the subscription plan.
     *
     * @param $email
     * @param $token
     * @param App\Billing\StripeSubscriptionGateway $subscriptionGateway
     * @return App\Models\Subscription
     */
    public function purchase($email, $token, $subscriptionGateway)
    {
        $sub = $subscriptionGateway->subscribe($email, $token, $this);

        return $this->subscriptions()->create([
            'stripe_id' => $sub['id'],
            'bundle_id' => $this->product,
            'bundle_name' => $this->product,
            'customer' => $sub['customer'],
            'email' => $email,
            'billing' => "charge_automatically",
            'amount' => $sub['plan']['amount'],
            'status' => $sub['status'],
            'start_date' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);
    }
}
