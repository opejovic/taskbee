<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use App\Exceptions\SubscriptionExistsException;
use App\Models\Bundle;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
	protected $paymentGateway;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct(PaymentGateway $paymentGateway)
	{
		$this->paymentGateway = $paymentGateway;
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Models\Bundle $bundle
     * @return \Illuminate\Http\Response
     */
    public function store(Bundle $bundle)
    {
        abort_if($bundle->hasActiveSubscriptionFor(request('email')), 422);

        request()->validate([
    		'email' => ['required', 'email'],
            'payment_token' => ['required'],
    	]);
        
        try {
            // $sub = $subscription->purchase($bundle, $paymentGateway, $email, $token)
            // $subscription->plan()->purchase($paymentGateway, $email, $token);
            $subscription = $bundle->purchase(
                $this->paymentGateway, 
                request('payment_token'), 
                request('email')
            );

    	    return response($subscription->toArray(), 201);
        } catch (PaymentFailedException $e) {
            return response([], 422);
        }
    }
}
