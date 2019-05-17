<?php

namespace App\Models;

use App\Exceptions\SubscriptionExistsException;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    /**
     * Class constants.
     *
     */
    const BASIC = 'basic';
    const ADVANCED = 'advanced';
    const PRO = 'pro';
    const BASIC_PRICE = 3995;
    const ADVANCED_PRICE = 6995;
    const PRO_PRICE = 9995;

    /**
     * Attributes that are not mass assignable.
     *
     */
    protected $guarded = [];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

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
    public function purchase($paymentGateway, $token, $email)
    {
        $paymentGateway->charge($this->price, $token);

        return $this->subscriptions()->create([
            'email' => $email,
            'bundle' => $this->name,
            'amount' => $paymentGateway->totalCharges(),
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
     * summary
     *
     * @return void
     * @author 
     */
    public function checkIfSubscriptionExists($email)
    {
        if ($this->hasActiveSubscriptionFor($email)) {
            throw new SubscriptionExistsException;
        }
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
