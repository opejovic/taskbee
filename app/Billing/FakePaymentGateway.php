<?php 

namespace App\Billing;

use App\Billing\PaymentGateway;

class FakePaymentGateway implements PaymentGateway
{
	protected $charges;
    
    /**
     * Create a new class instance.
     *
     * @return void
     */
	public function __construct()
	{
		$this->charges = collect();
	}

    /**
     * Retrieves the valid test payment token.
     *
     * @return string
     */
	public function getValidTestToken()
	{
		return 'valid-token';
	}

    /**
     * Increment the charges property for a given amount.
     *
     * @param $amount
     * @param $token
     */	
	public function charge($amount, $token)
	{
		if ($token !== $this->getValidTestToken()) {
			throw new PaymentFailedException;
		}

		$this->charges[] = $amount;
	}

    /**
     * Calculate total charges.
     *
     * @return integer
     */	
	public function totalCharges()
	{
		return $this->charges->sum();	
	}
}
