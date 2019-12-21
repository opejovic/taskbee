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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Stripe\Error\Api
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
     * @return mixed
     * @throws \Stripe\Error\Api
     */
    protected function retrieveStripeWebhooks()
    {
        return \Stripe\WebhookEndpoint::all([
            "limit" => 16, # Only 16 test web hooks can exist.
        ])['data'];
    }

    /**
     * Delete Stripe web hooks with specified endpoint.
     *
     * @param array $webhooks
     * @param string $endpoint
     */
    protected function delete($webhooks, $endpoint)
    {
        collect($webhooks)->filter(function ($webhook) use ($endpoint) {
            return $webhook['url'] === $endpoint;
        })->each->delete();

        $this->info('Web hooks deleted.');
    }
}
