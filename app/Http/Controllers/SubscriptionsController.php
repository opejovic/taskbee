<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Plan;
use taskbee\Billing\PaymentGateway;
use taskbee\Models\WorkspaceSetupAuthorization;

class SubscriptionsController extends Controller
{
    /**
     * Show the form for subscribing.
     *
     * @return \Illuminate\Http\Response
     **/
    public function create()
    {
        return view('subscriptions.create', [
            'plans' => Plan::all(),
        ]);
    }

    /**
     * Create a Checkout session, and charge the customer.
     *
     * @param \taskbee\Models\Plan $plan
     *
     * @param \taskbee\Billing\PaymentGateway $paymentGateway
     * @return \Stripe\Checkout\Session
     */
    public function store(Plan $plan, PaymentGateway $paymentGateway)
    {
        $session = $paymentGateway->checkout($plan);

        session(['sub' => $session]);

        return $session;
    }

    /**
     * Redirect customer after successful subscription.
     *
     * @param \taskbee\Billing\PaymentGateway $paymentGateway
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function success(PaymentGateway $paymentGateway)
    {
        # Get a StripeSubscription from Checkout Session.
        $subscription = $paymentGateway->getSubscription();
        
        # Get authorization code for that Subscription.
        $authorization = WorkspaceSetupAuthorization::getCodeFor($subscription);

        return redirect(route('workspace-setup.show', $authorization));
    }
}