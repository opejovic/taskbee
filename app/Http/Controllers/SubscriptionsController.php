<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\StripeSubscriptionGateway;
use App\Billing\SubscriptionGateway;
use App\Facades\AuthorizationCode;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\User;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SubscriptionsController extends Controller
{
    /**
     * Create a Stripe checkout session.
     *
     * @return Stripe\Checkout\Session
     */
    public function checkout(Plan $plan)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
        	'customer_email' => Auth::user()->email,
            'cancel_url' => "http://127.0.0.1:8000/plans",
            'expand' => ['subscription'],
            'payment_method_types' => ['card'],
            'subscription_data' => [
                'items' => [
                    [
                        'plan' => Plan::where('name', 'Base Fee')->first()->stripe_id, // base fee
                    ],
                    [
                        'plan' => $plan->stripe_id,
                        'quantity' => $plan->members_limit,
                    ],
                ],
            ],
            'success_url' => 'http://127.0.0.1:8000/success',
        ]);

        session(['sub' => $session]);

        return $session;
    }

    /**
     * Redirect customer after successful subscription.
     *
     * @return Illuminate\Routing\redirect
     */
    public function success()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // Get a StripeSubscription from Checkout Session.
        $sub = \Stripe\Checkout\Session::retrieve(request()->session()->get('sub')->id)['subscription'];

        // Get authorization code for that Subscription.
        $authorization = WorkspaceSetupAuthorization::where('subscription_id', $sub)->first()->code;
    	
        return redirect(route('workspace-setup.show', $authorization));
    }
}
