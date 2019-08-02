<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkspaceSetupAuthorization;

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
     * Create a Stripe checkout session.
     *
     * @param \App\Models\Plan $plan
     *
     * @return \Stripe\Checkout\Session
     */
    public function checkout(Plan $plan)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
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

        session(['sub' => $session]);

        return $session;
    }

    /**
     * Redirect customer after successful subscription.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
