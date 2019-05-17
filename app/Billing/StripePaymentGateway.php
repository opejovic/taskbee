<?php 

namespace App\Billing;

use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use Stripe\Error\InvalidRequest;

class StripePaymentGateway implements PaymentGateway
{
	private $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function charge($amount, $token)
	{
		try {
			return \Stripe\Charge::create([
				"amount" => $amount,
				"currency" => "eur",
			  	"source" => $token, // obtained with Stripe.js
			  	// "description" => "Charge for jenny.rosen@example.com"
			], ['api_key' => $this->apiKey]);
		} catch (InvalidRequest $e) {
			throw new PaymentFailedException;
		}
	}
}
