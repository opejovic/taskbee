<?php 

namespace App\Billing;

use App\Billing\PaymentFailedException;
use App\Billing\SubscriptionGateway;
use App\Models\Customer;
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
	 * Create a stripe customer and store it in the database.
	 *
	 * @param $email
	 * @param $token
	 * @return \Stripe\Customer
	 */
	public function createCustomer($email, $token)
	{
		try {
			// check if the customer with the given email exists
			if ($customer = Customer::where('email', $email)->first()) {
				return \Stripe\Customer::retrieve($customer->stripe_id,
					['api_key' => $this->apiKey]);
			}

			// create new stripe customer
			$stripeCustomer = \Stripe\Customer::create([
				"email" => $email,
			  	"source" => $token // obtained with Stripe.js
			], ['api_key' => $this->apiKey]);

			// create new customer in our db
			Customer::create([
				'email' => $email,
				'stripe_id' => $stripeCustomer->id
			]);

			return $stripeCustomer;
		} catch (InvalidRequest $e) {
			throw new PaymentFailedException;
		}
	}

	/**
	 * Create a stripe subscription for a customer.
	 *
	 * @param \Stripe\Customer $customer
	 * @param App\Models\Plan $plan
	 * @return \Stripe\Subscription
	 */
	public function createSubscriptionFor($customer, $plan)
	{
		return \Stripe\Subscription::create([
			"customer" => $customer->id,
			"items" => [
				[
				  "plan" => $plan->stripe_id ?: $plan->id, // for testing only (prod will be $plan->stripe_id)
				],
			]
		], ['api_key' => $this->apiKey]);
	}

	/**
	 * Create a stripe customer and a subscription for that customer.
	 *
	 * @param $email
	 * @param $token
	 * @param App\Models\Plan $plan
	 * @return \Stripe\Subscription
	 */
	public function subscribe($email, $token, $plan)
	{
		$customer = $this->createCustomer($email, $token);
		return $this->createSubscriptionFor($customer, $plan);
	}
}
