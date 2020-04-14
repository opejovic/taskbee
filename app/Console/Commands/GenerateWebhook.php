<?php

namespace taskbee\Console\Commands;

use Illuminate\Console\Command;

class GenerateWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:stripe-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a stripe web hook.';

    /**
     * Execute the console command.
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @return void
     */
    public function handle()
    {
        $this->info("Generating stripe web hook, please wait.");

        # @TODO Move it to the WebhookGateway
        \Stripe\WebhookEndpoint::create([
            "url" => config('services.ngrok.url'),
            "enabled_events" => [
                "customer.subscription.created",
                "customer.subscription.updated",
                "customer.subscription.deleted",
                "invoice.payment_succeeded",
                "checkout.session.completed",
            ]
        ], ['api_key' => config('services.stripe.secret')]);

        $this->info("Webhook generated.");
    }
}
