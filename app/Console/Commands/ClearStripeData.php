<?php

namespace taskbee\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class ClearStripeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:stripe-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clear apps stripe plans and webhooks.";

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
     */
    public function handle()
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            # Retrieve all TaskBee products from stripe
            $stripeProducts = collect(\Stripe\Product::all()['data'])->filter(function ($product) {
                return $product['name'] == 'TaskBee Workspace Bundle';
            });

            throw_if($stripeProducts->isEmpty(), new \Exception('No TaskBee products exist on Stripe.'));

            # Delete the plans associated with TaskBee
            $stripeProducts->map(function ($product) {
                return $product['id'];
            })->map(function ($product) {
                return \Stripe\Plan::all(['product' => $product])['data'];
            })->each(function ($productPlans) {
                collect($productPlans)->each(function ($plan) {
                    $plan->delete();
                });
            });

            # Delete the TaskBee products
            $stripeProducts->each->delete();

            $this->info('Done');
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
        
    }
}
