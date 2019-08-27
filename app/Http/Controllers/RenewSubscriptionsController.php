<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Workspace;

class RenewSubscriptionsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param \taskbee\Models\Workspace $workspace
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
     * @param \taskbee\Models\Workspace $workspace
     * @return \Illuminate\Http\Response
     */
    public function store(Workspace $workspace)
    {
    	\Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        # Retrieve subscription from stripe
        $stripeSub = \Stripe\Subscription::retrieve([
        	"id" => $workspace->subscription->stripe_id,
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
