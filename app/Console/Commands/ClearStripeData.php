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
    protected $description = "Clear apps stripe plans and web hooks.";

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
     * @throws \Throwable
     */
    public function handle()
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $this->info('Interacting with Stripe, please wait.');

            # Retrieve all TaskBee products from stripe
            $stripeProducts = $this->products();
            throw_if($stripeProducts->isEmpty(), new Exception('No TaskBee products exist on Stripe.'));

            # Delete the plans associated with TaskBee
            $this->deletePlansFor($stripeProducts);

            # Delete the TaskBee products
            $stripeProducts->each->delete();
            $this->info('Stripe products and plans deleted.');
        } catch (Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * Delete all the plans that belong to given products.
     *
     * @param \Illuminate\Support\Collection $products
     */
    public function deletePlansFor($products)
    {
        # @TODO Refactor
        $products->map(function ($product) {
            return $product['id'];
        })->map(function ($product) {
            return \Stripe\Plan::all(['product' => $product])['data'];
        })->each(function ($productPlans) {
            collect($productPlans)->each(function ($plan) {
                $plan->delete();
            });
        });
    }

    /**
     * Get the stripe products.
     *
     * @return \Stripe\Product
     */
    public function products()
    {
        return collect(\Stripe\Product::all()['data'])->filter(function ($product) {
            return $product['name'] == 'TaskBee Workspace Bundle';
        });
    }
}