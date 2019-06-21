<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;

class RenewSubscriptionsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param App\Models\Workspace $workspace
     * @return \Illuminate\Http\Response
     */
	public function show(Workspace $workspace)
	{
	    abort_unless($workspace->subscription->isExpired(), 404);

	    return view('subscription-expired.show', ['workspace' => $workspace]);
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Models\Workspace $workspace
     * @return \Illuminate\Http\Response
     */
    public function store(Workspace $workspace)
    {
        $subscription = $workspace->subscription;

         // retrieve subscription
    	\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        $stripeSub = \Stripe\Subscription::retrieve([
        	"id" => $subscription->stripe_id,
        ]);

        $invoice = \Stripe\Invoice::create([
            "customer" => $stripeSub['customer'],
            "subscription" => $stripeSub['id'],
            "description" => 'Renew subscription'
        ]);

        $finalizedInvoice = $invoice->finalizeInvoice();
        return response($finalizedInvoice, 200);
    }
}
