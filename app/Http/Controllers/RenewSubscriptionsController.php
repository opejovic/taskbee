<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;

class RenewSubscriptionsController extends Controller
{
	/**
	 * summary
	 *
	 * @return void
	 * @author 
	 */
	public function show(Workspace $workspace)
	{
	    abort_unless($workspace->subscription->isExpired(), 404);

	    return view('subscription-expired.show', ['workspace' => $workspace]);
	}

    /**
     * summary
     *
     * @return void
     * @author 
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
