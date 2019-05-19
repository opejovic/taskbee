<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\SubscriptionGateway;
use App\Facades\InvitationCode;
use App\Mail\InvitationEmail;
use App\Mail\SubscriptionPurchaseEmail;
use App\Models\Invitation;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriptionsController extends Controller
{
	private $subscriptionGateway;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct(SubscriptionGateway $subscriptionGateway)
	{
		$this->subscriptionGateway = $subscriptionGateway;
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Models\Plan $plan
     * @return \Illuminate\Http\Response
     */
    public function store(Plan $plan)
    {
    	request()->validate([
    		'email' => ['required', 'email'],
    		'payment_token' => ['required'],
    	]);

    	try {
            $subscription = $plan->purchase(
                request('email'), 
                request('payment_token'), 
                $this->subscriptionGateway
            );

            $invitation = Invitation::create([
                'email' => request('email'),
                'user_role' => User::ADMIN,
                'code'  => InvitationCode::generate(),
                'subscription_id' => $subscription->id,
            ]);
            
            Mail::to($subscription->email)->queue(new SubscriptionPurchaseEmail($subscription, $invitation));
            
	    	return response($subscription, 201);
    	} catch (PaymentFailedException $e) {
    		return response([], 422);
    	}
    }
}
