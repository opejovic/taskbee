<?php

namespace Feature;

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;
use App\Exceptions\SubscriptionExistsException;
use App\Models\Bundle;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseBundleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $this->paymentGateway);
        $this->bundle = factory(Bundle::class)->create([
            'price' => 3995,
        ]);
    }

    /** @test */
    function customers_can_purchase_workspace_bundles()
    {
        $response = $this->json('POST', "/bundles/{$this->bundle->name}/pay", [
            'email' => 'john@example.com',
            'bundle' => 'basic',
            'payment_token' => $this->paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'email' => 'john@example.com',
            'bundle' => 'basic',
            'amount' => 3995,
        ]);
        $this->assertEquals(3995, $this->paymentGateway->totalCharges());

        $subscription = $this->bundle->subscriptions()
            ->where('email', 'john@example.com')->first();

        $this->assertNotNull($subscription);
    }

    /** @test */
    function subscription_is_not_created_if_payment_fails()
    {
        $response = $this->json('POST', "/bundles/{$this->bundle->name}/pay", [
            'email' => 'john@example.com',
            'payment_token' => "invalid-token",
        ]);

        $response->assertStatus(422);
        $subscription = $this->bundle->subscriptions()->first();
        $this->assertNull($subscription);
        $this->assertEquals(0, $this->paymentGateway->totalCharges());
    }

    /** @test */
    function customer_cannot_purchase_a_bundle_if_he_is_already_subscribed_to_it()
    {
        $subscription = $this->bundle->purchase(
            $this->paymentGateway, 
            $this->paymentGateway->getValidTestToken(), 
            'john@example.com'
        );

        $this->assertTrue(Subscription::first()->is($subscription));
        $this->assertEquals(3995, $this->paymentGateway->totalCharges());
        
        $newPaymentGateway = new FakePaymentGateway;
        $response = $this->json('POST', "/bundles/{$this->bundle->name}/pay", [
            'email' => 'john@example.com',
            'payment_token' => $newPaymentGateway->getValidTestToken(),
        ]);    
        $response->assertStatus(422);
        $this->assertEquals(0, $newPaymentGateway->totalCharges());
        $this->assertCount(1, Subscription::all());
    }

    /** @test */
    function email_is_required_to_purchase_a_bundle()
    {
        $response = $this->json('POST', "/bundles/{$this->bundle->name}/pay", [
            'payment_token' => $this->paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
        $this->assertEquals(0, Subscription::count());
        $this->assertEquals(0, $this->paymentGateway->totalCharges());
    }

    /** @test */
    function payment_token_is_required_to_purchase_a_bundle()
    {
        $response = $this->json('POST', "/bundles/{$this->bundle->name}/pay", [
            'email' => 'john@example.com'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('payment_token');
        $this->assertEquals(0, Subscription::count());
        $this->assertEquals(0, $this->paymentGateway->totalCharges());
    }
}
