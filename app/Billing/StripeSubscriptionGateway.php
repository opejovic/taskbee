<?php 

namespace App\Billing;

use App\Billing\PaymentFailedException;
use App\Billing\SubscriptionGateway;
use App\Facades\AuthorizationCode;
use App\Models\Bundle;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WorkspaceSetupAuthorization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Error\InvalidRequest;

class StripeSubscriptionGateway implements SubscriptionGateway
{
	private $apiKey;

    /**
     * Create a new class instance.
     *
     * @return void
     */
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	/**
	 * Fulfills the process of subscribing the customer after a successful payment.
	 *
	 * @param json $session
	 * @return void
	 */
	public function fulfill($session)
	{
	    $customer = \Stripe\Customer::retrieve($session->customer);

	    if (! Customer::where('email', $customer['email'])->exists()) {
			Customer::create([
				'email' => $customer['email'],
				'stripe_id' =>$customer['id'],
			]);
		}

		$subscription = \Stripe\Subscription::retrieve($session->subscription);

		$sub = Subscription::create([
			'stripe_id'   => $subscription['id'],
            'bundle_id'   => $subscription['plan']['product'],
            'plan_id'     => $subscription['plan']['id'],
            'plan_name'   => $subscription['plan']['nickname'],
            'bundle_name' => Bundle::where('stripe_id', $subscription['plan']['product'])->first()->name,
            'customer'    => $subscription['customer'],
            'email'       => $customer['email'],
            'billing' 	  => $subscription['billing'],
            'amount' 	  => $subscription['plan']['amount'],
            'status' 	  => $subscription['status'],
            'start_date'  => Carbon::createFromTimestamp($subscription['current_period_start']),
            'expires_at'  => Carbon::createFromTimestamp($subscription['current_period_end']),
		]);

        WorkspaceSetupAuthorization::create([
            'email' => $customer['email'],
            'user_role' => User::ADMIN,
            'members_invited' => 1,
            'code' => AuthorizationCode::generate(),
            'subscription_id'=> $sub->id,
            'plan_id'=> $subscription['plan']['id'],
            'members_limit' => $sub->bundle->members_limit,
        ])->send($sub);
	}
}
