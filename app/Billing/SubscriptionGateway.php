<?php 

namespace App\Billing;

interface SubscriptionGateway 
{
	public function createCustomer($customer, $token);
	public function createSubscriptionFor($customer, $plan);
}
