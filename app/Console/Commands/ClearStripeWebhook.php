<?php

namespace taskbee\Console\Commands;

use Illuminate\Console\Command;

class ClearStripeWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:stripe-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clear Stripe's web hook.";

    /**
     * Execute the console command.
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @return mixed
     */
    public function handle()
    {
        $this->info('Clearing web hooks from Stripe... please wait.');

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $webhooks = $this->retrieveStripeWebhooks();

        $this->delete($webhooks, config('services.ngrok.url'));
    }

    /**
     * Retrieve web hooks from Stripe API.
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @return mixed
     */
    protected function retrieveStripeWebhooks(): mixed
    {
        return \Stripe\WebhookEndpoint::all([
            'limit' => 16, # Only 16 test web hooks can exist.
        ])['data'];
    }

    /**
     * Delete Stripe web hooks with specified endpoint.
     *
     * @param  array  $webhooks
     * @param  string $endpoint
     */
    protected function delete($webhooks, $endpoint): void
    {
        collect($webhooks)->filter(function ($webhook) use ($endpoint) {
            return $webhook['url'] === $endpoint;
        })->each->delete();

        $this->info('Web hooks deleted.');
    }
}
