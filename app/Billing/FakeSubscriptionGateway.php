<?php 

namespace App\Billing;

use App\Billing\SubscriptionGateway;
use App\Models\Customer;
use App\Models\Subscription;
use Carbon\Carbon;

class FakeSubscriptionGateway implements SubscriptionGateway
{
	public function getValidTestToken()
	{
		return "valid-token";
	}

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

	public function subscribe($email, $token, $plan)
	{
		$customer = $this->createCustomer($email, $token);
		return $this->createSubscriptionFor($customer, $plan);
	}
}
