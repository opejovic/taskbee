<?php

namespace Feature;

use Carbon\Carbon;
use Tests\TestCase;
use taskbee\Models\Plan;
use taskbee\Models\User;
use taskbee\Models\Bundle;
use taskbee\Models\Customer;
use taskbee\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use taskbee\Facades\AuthorizationCode;
use taskbee\Billing\SubscriptionGateway;
use Illuminate\Foundation\Testing\WithFaker;
use taskbee\Billing\FakeSubscriptionGateway;
use taskbee\Mail\SubscriptionPurchasedEmail;
use taskbee\Models\WorkspaceSetupAuthorization;
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
    }

    /** @test */
    public function guests_cannot_purchase_a_bundle_subscription()
    {
        $response = $this->json('POST', "plans/1/checkout", [])->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_subscribe_to_a_plan_with_successful_purchase()
    {
        // I just call the stripes checkout, which prompts the screen. THen the user enters the
        // card number etc, that passes and we get the webhook back.
        // So I need to figure out how to submit a payment to stripe,
        // so that I activate the webhook. Not sure how to get the ngrok / valet running in tests tho

        Mail::fake();
        AuthorizationCode::shouldReceive('generate')->andReturn('TESTCODE123');

        $user = factory(User::class)->create();

        $plan = $this->SetupStripe();
        $created_at = Carbon::now()->unix();

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $token = $this->getTestToken();

        $response = $this->actingAs($user)->json('POST', "/plans/{$plan->id}/checkout", [
            'token' => $token
        ]);

        dd(session());

        $sup = $this->get("http://taskbee.test/success");

        $subscription = $plan->subscriptions()->where('email', 'jane@example.com')->first();
        $this->assertNotNull($subscription);
        $this->assertEquals(3995, $subscription->amount);

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

        $product = \Stripe\Product::create([
            "name" => 'Testing Workspace Bundle',
            "type" => "service",
            "metadata" => [
                "members_limit" => 5,
            ],
        ]);

        $basicPlan = \Stripe\Plan::create([
            "amount" => 3995,
            "interval" => "month",
            "product" => $product['id'],
            "currency" => "eur",
        ]);

        return Plan::create([
            "name" => $basicPlan['nickname'],
            "amount" => $basicPlan['amount'],
            "interval" => "month",
            "members_limit" => 5,
            "currency" => "eur",
            "stripe_id" => $basicPlan['id'],
        ]);
    }

    protected function getTestToken()
    {
        return \Stripe\Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 5,
                'exp_year' => 2020,
                'cvc' => '123',
            ],
        ], ['api_key' => getenv('STRIPE_SECRET')])->id;
    }
}
