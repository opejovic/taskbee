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
	public function fulfill($subscription)
	{
		if ($this->subscriptionPaid($subscription)) {
			$this->setupSubscription($subscription);	
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
					'id' => $subscription->id,
					'expand' => ['latest_invoice']
    			])['latest_invoice']['paid'];
	}

	/**
	 * Store subscription in the db.
	 *
	 */
	public function setupSubscription($subscription)
	{
	    $customer = \Stripe\Customer::retrieve($subscription->customer);

	    // If the customer with the given email doesent exist, create it.
	    if (! Customer::where('email', $customer['email'])->exists()) {
			Customer::create([
				'email' => $customer['email'],
				'stripe_id' =>$customer['id'],
			]);
		}

		Subscription::buildFrom(
			\Stripe\Subscription::retrieve($subscription->id),
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
		switch ($invoice->description) {
            // ... handle the Add additional member slot payment
            case 'Add additional member slot':
				Workspace::addSlot($invoice->subscription);
                break;
            
            // ... handle the subscription renewal invoice.payment_succeeded for 
            case 'Renew subscription':
                // Subscription::renew($invoice->subscription);
                break;

            default:
                // Unexpected event type
                exit();
	    }
	    // Add additional checks for the paid invoice
        // need to notify the customer that he can now invite additional member.
	}

	/**
	 * Inspects the subscription status, after the subscription updated event,
	 * and updates subscription locally.
	 *
	 * @return void
	 */
	public function inspect($subscription)
	{
	    if (collect([
	    	Subscription::PAST_DUE,
	    	Subscription::UNPAID,
	    	Subscription::CANCELED
	    ])->contains($subscription->status)) {
	    	Subscription::changeStatus($subscription);
	    }
	}
}
