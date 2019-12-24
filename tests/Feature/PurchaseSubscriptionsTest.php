<?php

namespace Feature;

use Tests\TestCase;
use taskbee\Models\Plan;
use taskbee\Models\User;
use taskbee\Models\Bundle;
use taskbee\Models\Customer;
use taskbee\Models\Subscription;
use taskbee\Facades\AuthorizationCode;
use taskbee\Billing\SubscriptionGateway;
use Illuminate\Support\Facades\Mail;
use taskbee\Billing\FakeSubscriptionGateway;
use taskbee\Mail\SubscriptionPurchasedEmail;
use taskbee\Models\WorkspaceSetupAuthorization;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group integration
 */
class PurchaseSubscriptionsTest extends TestCase
{
    use RefreshDatabase;
    // @TODO Make this test work.
    protected function setUp(): void
    {
        parent::setUp();
        $this->subscriptionGateway = new FakeSubscriptionGateway;
        $this->app->instance(SubscriptionGateway::class, $this->subscriptionGateway);
        $this->plan = factory(Plan::class)->create([
            'amount' => 2500,
            'product' => factory(Bundle::class)->create()->stripe_id,
        ]);
    }

    /** @test */
    public function guests_cannot_purchase_a_bundle_subscription()
    {
        $response = $this->json('POST', "plans/{$this->plan->id}/checkout", [])->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_subscribe_to_a_plan_with_successful_purchase()
    {
        $this->withoutExceptionHandling();
        Mail::fake();
        AuthorizationCode::shouldReceive('generate')->andReturn('TESTCODE123');

        $user = factory(User::class)->create();

        $plan = $this->SetupStripe();

        Token::create();

        $response = $this->actingAs($user)->json('POST', "/plans/{$plan->id}/checkout", [
            'email' => 'jane@example.com',
            'tok' => $tok,
        ]);

        $subscription = $plan->subscriptions()->where('email', 'jane@example.com')->first();
        $this->assertNotNull($subscription);
        $this->assertEquals(2500, $subscription->amount);

        $setupAuthorization = WorkspaceSetupAuthorization::first();
        $this->assertNotNull($setupAuthorization);
        $this->assertEquals('jane@example.com', $setupAuthorization->email);
        $this->assertEquals('TESTCODE123', $setupAuthorization->code);

        Mail::assertQueued(
            SubscriptionPurchasedEmail::class,
            function ($mail) use ($subscription, $setupAuthorization) {
                return $mail->hasTo('jane@example.com')
                    && $mail->setupAuthorization->is($setupAuthorization)
                    && $mail->subscription->id == $subscription->id;
            }
        );
    }

    /** @test */
    public function subscription_is_not_created_if_payment_fails()
    {
        $response = $this->json('POST', "/bundles/{$this->plan->id}/purchase", [
            'email' => 'jane@example.com',
            'token' => "invalid-token",
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, Customer::all());
        $this->assertCount(0, Subscription::all());
    }

    /** @test */
    public function email_is_required_to_purchase_a_subscription()
    {
        $response = $this->json('POST', "/bundles/{$this->plan->id}/purchase", [
            'token' => $this->subscriptionGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
        $this->assertCount(0, Customer::all());
        $this->assertCount(0, Subscription::all());
    }

    /** @test */
    public function token_is_required_to_purchase_a_subscription()
    {
        $response = $this->json('POST', "/bundles/{$this->plan->id}/purchase", [
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('payment_token');
        $this->assertCount(0, Customer::all());
        $this->assertCount(0, Subscription::all());
    }

    private function setupStripe()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $basicBundle = \Stripe\Product::create([
            "name" => 'Testing Workspace Bundle',
            "type" => "service",
            "metadata" => [
                "members_limit" => 5,
            ],
        ]);

        $basicPlan = \Stripe\Plan::create([
            "amount" => 3995,
            "interval" => "month",
            "product" => $basicBundle['id'],
            "currency" => "eur",
        ]);

        return Plan::create([
            "name" => $basicPlan['nickname'],
            "amount" => $basicPlan['amount'],
            "interval" => "month",
            "product" => $basicBundle['id'],
            "currency" => "eur",
            "stripe_id" => $basicPlan['id'],
        ]);
    }
}
