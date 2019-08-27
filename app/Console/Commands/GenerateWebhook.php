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
     * @return void
     */
    public function handle()
    {
        $this->info("Generating stripe web hook.. please wait.");

        # @TODO Move it to the WebhookGateway

        \Stripe\WebhookEndpoint::create([
            "url" => config('services.ngrok.url') . "/stripe-webhook",
            "enabled_events" => [
                "customer.subscription.created",
                "customer.subscription.updated",
                "customer.subscription.deleted",
                "invoice.payment_succeeded",
                "checkout.session.completed",
            ]
        ], ['api_key' => config('services.stripe.secret')]);

        $this->info("Done!");
    }
}
