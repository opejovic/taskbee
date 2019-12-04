<?php

namespace Tests\Feature;

use Tests\TestCase;
use taskbee\Models\Plan;
use taskbee\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StripeCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_authenticated_users_can_view_checkout_page()
    {
        $this->post("/plans/1/checkout")->assertRedirect('login');
    }

    /** @test */
    public function it_creates_a_checkout_session()
    {
        # Arrange: Existing plan
        $user = factory(User::class)->create();
        $plan = factory(Plan::class)->state('basic')->create();
        $gateway = \Mockery::spy('taskbee\Billing\PaymentGateway');
        app()->instance('taskbee\Billing\PaymentGateway', $gateway);

        # Act: visit checkout endpoint
        $this->actingAs($user)->post("/plans/{$plan->id}/checkout");

        # Assert: the gateway received a checkout method
        $gateway->shouldHaveReceived('checkout')->once();
    }
}
