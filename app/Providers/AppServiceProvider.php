<?php

namespace App\Providers;

use App\Billing\PaymentGateway;
use App\Billing\StripePaymentGateway;
use App\Billing\StripeSubscriptionGateway;
use App\Billing\SubscriptionGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StripePaymentGateway::class, function () {
            return new StripePaymentGateway(config('services.stripe.secret'));
        });

        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
        
        $this->app->bind(StripeSubscriptionGateway::class, function () {
            return new StripeSubscriptionGateway(config('services.stripe.secret'));
        });

        $this->app->bind(SubscriptionGateway::class, StripeSubscriptionGateway::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
