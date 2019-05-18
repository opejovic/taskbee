<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\SubscriptionGateway;
use App\Models\Bundle;
use Illuminate\Http\Request;

class PurchaseSubscriptionsController extends Controller
{
	protected $subGateway;

	public function __construct(SubscriptionGateway $subGateway)
	{
		$this->subGateway = $subGateway;
	}

    public function store(Bundle $bundle)
    {
    	request()->validate([
    		'email' => ['required', 'email'],
    		'token' => ['required'],
    	]);

    	try {
    		$subscription = $bundle->purchase(request('email'), request('token'), $this->subGateway);
	    	return response($subscription, 201);
    	} catch (PaymentFailedException $e) {
    		return response([], 422);
    	}
    }
}
