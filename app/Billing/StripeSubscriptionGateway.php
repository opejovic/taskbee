<?php 

namespace App\Billing;

use App\Billing\SubscriptionGateway;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;

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
		if ($this->subscriptionPaid($session->subscription)) {
			$this->setupSubscription($session);	
		}
	}

	/**
	 * Is subscription paid for?
	 *
	 * @return bool
	 */
	public function subscriptionPaid($subscription)
	{
	    return \Stripe\Subscription::retrieve([
					'id' => $subscription,
					'expand' => ['latest_invoice']
    			])['latest_invoice']['paid'];
	}

	/**
	 * Store subscription in the db.
	 *
	 */
	private function setupSubscription($session)
	{
	    $customer = \Stripe\Customer::retrieve($session->customer);

	    // If the customer with the given email doesent exist, create it.
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

	/**
	 * Handles the invoice payment succeeded event.
	 *
	 * @return void
	 * @author 
	 */
	public function handleInvoice($invoice)
	{
	    if (! $invoice->description == "Add additional member slot") return;

	    Workspace::addSlot($invoice->subscription);
        // need to notify the customer that he can now invite additional member.
	}
}
