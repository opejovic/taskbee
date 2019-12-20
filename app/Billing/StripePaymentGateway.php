<?php

namespace taskbee\Billing;

use taskbee\Models\Plan;
use Illuminate\Support\Facades\Auth;

class StripePaymentGateway implements PaymentGateway
{
    /**
     * @var void
     */
    private $apiKey;

    /**
     * StripePaymentGateway constructor.
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = \Stripe\Stripe::setApiKey($apiKey);
    }

    /**
     * Create a Stripe Checkout session, and charge the customer.
     *
     * @param  \taskbee\Models\Plan $plan
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\Checkout\Session
     */
    public function checkout(Plan $plan)
    {
        return \Stripe\Checkout\Session::create([
            'customer_email'       => Auth::user()->email,
            'cancel_url'           => config('app.url') . "/plans",
            'expand'               => ['subscription'],
            'payment_method_types' => ['card'],
            'subscription_data'    => [
                'items' => [
                    [
                        'plan'     => $plan->stripe_id,
                        'quantity' => $plan->members_limit,
                    ],
                ],
            ],
            'success_url'          => config('app.url') . "/success",
        ]);
    }

    /**
     * Get Stripe subscription from the checkout session.
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @return mixed
     */
    public function getSubscription()
    {
        return \Stripe\Checkout\Session::retrieve(
            request()->session()->get('sub')->id
        )['subscription'];
    }
}
