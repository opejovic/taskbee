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
     * @param \taskbee\Models\Plan $plan
     * @return \Stripe\Checkout\Session
     */
    public function checkout(Plan $plan)
    {
        return \Stripe\Checkout\Session::create([
            'customer_email'       => Auth::user()->email,
            'cancel_url'           => "http://127.0.0.1:8000/plans",
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
            'success_url'          => 'http://127.0.0.1:8000/success',
        ]);
    }

    /**
     * Get Stripe subscription from the checkout session.
     *
     * @return mixed
     */
    public function getSubscription()
    {
        return \Stripe\Checkout\Session::retrieve(
            request()->session()->get('sub')->id
        )['subscription'];
    }
}
