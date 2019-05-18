<?php

namespace App\Models;

use App\Exceptions\SubscriptionExistsException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    /**
     * Class constants.
     *
     */
    const BASIC = 'Basic Workspace Bundle';
    const ADVANCED = 'Advanced Worksspace Bundle';
    const PRO = 'Pro Workspace Bundle';
    const BASIC_PRICE = 3995;
    const ADVANCED_PRICE = 6995;
    const PRO_PRICE = 9995;

    /**
     * Attributes that are not mass assignable.
     *
     */
    protected $guarded = [];

    // *
    //  * Get the route key for the model.
    //  *
    //  * @return string
     
    // public function getRouteKeyName()
    // {
    //     return 'name';
    // }

    /**
     * Bundle has many subscriptions.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Purchase a subscription for the bundle.
     *
     * @param App\Billing\PaymentGateway $paymentGateway
     * @param $token
     * @param $email
     * @return App\Models\Subscription
     */
    public function purchase($email, $token, $subscriptionGateway)
    {
        $sub = $subscriptionGateway->createSubscriptionFor($customer, $this);
        
        return $this->subscriptions()->create([
            'bundle' => $this->name,
            'customer' => $customer->id,
            'email' => $customer->email,
            'billing' => "charge_automatically",
            'plan_id' => $this->stripe_id,
            'amount' => $this->price,
            'status' => 'active',
            'start_date' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);
    }

    /**
     * Create a new subscription instance for a given email.
     *
     * @param $email
     * @return App\Models\Subscription
     */
    public function addSubscriptionFor($email)
    {
        return $this->subscriptions()->create([
            'email' => $email,
            'bundle' => $this->name,
            'amount' => $this->price,
        ]);
    }

    /**
     * Does subscription for the given email exists?
     *
     * @param $email
     * @return bool
     */
    public function hasActiveSubscriptionFor($email)
    {
        return $this->subscriptions()->where('email', $email)->exists();
    }
}
