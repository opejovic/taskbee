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
	private $subscriptionGateway;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SubscriptionGateway $subscriptionGateway)
    {
      $this->subscriptionGateway = $subscriptionGateway;
  	}

    public function checkout(Plan $plan)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        return \Stripe\Checkout\Session::create([
        	'customer_email' => Auth::user()->email,
            'payment_method_types' => ['card'],
            'subscription_data' => [
                'items' => [[
                    'plan' => $plan->stripe_id,
                ]],
            ],
            'success_url' => 'http://127.0.0.1:8000/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);
    }

    public function success()
    {
    	$authorization = WorkspaceSetupAuthorization::where('email', auth()->user()->email)->latest()->first()->code;

    	return redirect(route('workspace-setup.show', $authorization));
    }
}
