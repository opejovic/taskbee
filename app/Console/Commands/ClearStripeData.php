<?php

namespace taskbee\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ClearStripeData extends Command
{
    /**
     * The name of the product on stripe.
     */
    const NAME = 'TaskBee Workspace Bundle';

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
    protected $description = 'Clear stripe plans and web hooks.';

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
    public function deletePlansFor($products): void
    {
        $products->map(function ($product) {
            return $product['id'];
        })->flatMap(function ($product) {
            return \Stripe\Plan::all(['product' => $product])['data'];
        })->each(function ($plan) {
            $plan->delete();
        });
    }

    /**
     * Get the stripe products.
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Illuminate\Support\Collection
     */
    public function products(): Collection
    {
        return collect(\Stripe\Product::all()['data'])->filter(function ($product) {
            return $product['name'] === self::NAME;
        });
    }
}
