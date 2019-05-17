<?php

namespace Tests\Unit\Billing;

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FakePaymentGatewayTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function charges_with_valid_payment_token_are_successful()
	{
	    $paymentGateway = new FakePaymentGateway;
	    $paymentGateway->charge(4000, $paymentGateway->getValidTestToken());
	    $this->assertEquals(4000, $paymentGateway->totalCharges());
	}

	/** @test */
	function charges_with_an_invalid_payment_token_fail()
	{
	    try {
	    	$paymentGateway = new FakePaymentGateway;
	    	$paymentGateway->charge(4000, 'invalid-token');
	    } catch (PaymentFailedException $e) {
	    	$this->assertEquals(0, $paymentGateway->totalCharges());
	    	return;
	    }
	    
	    $this->fail("Payment succeeded even though the invalid payment token was provided.");
	}
}
