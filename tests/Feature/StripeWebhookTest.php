<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/** @group integration */
class StripeWebhookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        # Stripe API key is necessary.
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    /** @test */
    function stripe_webhooks_are_generated_via_console_command()
    {
        # Call artisan command for generating web hooks.
        $created_at = Carbon::now()->unix();
        $this->artisan('generate:stripe-webhook');

        # Assert there is one web hook created
        $data = \Stripe\WebhookEndpoint::all([
            "limit" => 16, # Only 16 test web hooks can exist.
        ])['data'];

        $webhooks = collect($data)->filter(function ($item) use ($created_at) {
           return $item['created'] >= $created_at;
        });

        $this->assertEquals(1, count($webhooks));

        # Clean up - delete created web hook.
        $webhooks->each->delete();
    }

    /** @test */
    function stripe_webhooks_can_be_deleted_via_console_command()
    {
        # Arrange
        $created_at = Carbon::now()->unix();
        $this->artisan('generate:stripe-webhook');

        $dataAfterGenerating = \Stripe\WebhookEndpoint::all([
            "limit" => 16, # Only 16 test web hooks can exist.
        ])['data'];

        $webhooks = collect($dataAfterGenerating)->filter(function ($item) use ($created_at) {
            return $item['created'] >= $created_at && $item['url'] == config('services.ngrok.url');
        });

        # Assert
        $this->assertEquals(1, count($webhooks));

        # Act - find web hooks with the $endpoint

        # This will clear every web hook with $endpoint...
        # Use this test with caution, it will delete all your current web hooks with $endpoint.
        $this->artisan('clear:stripe-webhook');

        $dataAfterDeleting = \Stripe\WebhookEndpoint::all([
            "limit" => 16, # Only 16 test web hooks can exist.
        ])['data'];

        $webhooksAfterDeleting = collect($dataAfterDeleting)->filter(function ($item) {
            return $item['url'] == config('services.ngrok.url');
        });

        $this->assertTrue($webhooksAfterDeleting->isEmpty());
    }
}
