<?php

namespace Tests\Unit\Billing;

use App\Billing\PaymentFailedException;
use App\Billing\StripePaymentGateway;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->lastCharge = $this->lastCharge();
	}

	/** @test */
	function charges_with_valid_payment_token_are_successful()
	{
		$paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));

		$paymentGateway->charge(2500, $this->validToken());

		$this->assertCount(1, $this->newCharges());
		$this->assertEquals(2500, $this->lastCharge()->amount);
	}

	/** @test */
	function charges_with_invalid_payment_token_fail()
	{
		try {
			$paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));
			$paymentGateway->charge(2400, "invalid-token");
		} catch (PaymentFailedException $e) {
			$this->assertCount(0, $this->newCharges());
			return;
		}

		$this->fail("Payment succeeded even though the token provide was invalid.");
	}

	private function validToken()
	{
		return \Stripe\Token::create([
			'card' => [
				'number' => '4242424242424242',
				'exp_month' => 12,
				'exp_year' => date('Y') + 1,
				'cvc' => '123'
			]
		], ['api_key' => config('services.stripe.secret')])['id'];
	}

	private function lastCharge()
	{
		return \Stripe\Charge::all([
			"limit" => 1
		], ['api_key' => config('services.stripe.secret')])['data'][0];
	}

	private function newCharges()
	{
		return \Stripe\Charge::all(
			[
				"limit" => 5,
				"ending_before" => $this->lastCharge,
			], 
			['api_key' => config('services.stripe.secret')]
		)['data'];
	}
}
