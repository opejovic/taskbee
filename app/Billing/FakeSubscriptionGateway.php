<?php 

namespace App\Billing;

use App\Billing\SubscriptionGateway;
use App\Models\Customer;
use App\Models\Subscription;
use Carbon\Carbon;

class FakeSubscriptionGateway implements SubscriptionGateway
{
	/**
	 * Return a valid test token.
	 *
	 * @return string
	 */
	public function getValidTestToken()
	{
		return "valid-token";
	}

	/**
	 * Create a customer and a subscription for that customer.
	 *
	 * @param $email
	 * @param $token
	 * @param App\Models\Plan $plan
	 * @return array
	 */
	public function subscribe($email, $token, $plan)
	{
		$customer = $this->createCustomer($email, $token);
		return $this->createSubscriptionFor($customer, $plan);
	}

	/**
	 * Create a customer.
	 *
	 * @param $email
	 * @param $token
	 * @return App\Models\Customer
	 */
	public function createCustomer($email, $token)
	{
		if ($token !== $this->getValidTestToken()) {
			throw new PaymentFailedException;
		}

		if ($customer = Customer::where('email', $email)->first()) {
			return $customer;
		}

		return Customer::create([
			"email" => $email,
			"stripe_id" => "cus_RANDOMID123",
		]);
	}

	/**
	 * Create a subscription for a customer.
	 *
	 * @param App\Models\Customer $customer
	 * @param App\Models\Plan $plan
	 * @return array
	 */
	public function createSubscriptionFor($customer, $plan)
	{
		return [
			'id' => $plan,
			'bundle' => $plan,
			'customer' => $customer['id'],
			'email' => $customer['email'],
			'billing' => "charge_automatically",
			'plan_id' => $plan,
			'plan' => [
				'amount' => $plan->amount,
			],
			'status' => 'active',
			'start_date' => Carbon::now(),
			'expires_at' => Carbon::now()->addMonth(),
		];
	}
}
