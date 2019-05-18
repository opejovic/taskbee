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
		]);
	}

	public function createSubscriptionFor($customer, $bundle)
	{
		return Subscription::create([
			'bundle_id' => $bundle->id,
			'bundle' => $bundle->name,
			'customer' => $customer->id,
			'email' => $customer->email,
			'billing' => "charge_automatically",
			'plan_id' => $bundle->stripe_id,
			'amount' => $bundle->price,
			'status' => 'active',
			'start_date' => Carbon::now(),
			'expires_at' => Carbon::now()->addMonth(),
		]);
	}
}
