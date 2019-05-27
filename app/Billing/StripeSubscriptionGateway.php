<?php 

namespace App\Billing;

use App\Billing\SubscriptionGateway;
use App\Models\Customer;
use App\Models\Subscription;

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

		Subscription::buildFrom(
			\Stripe\Subscription::retrieve($session->subscription), 
			$customer['email']
		);
	}
}
