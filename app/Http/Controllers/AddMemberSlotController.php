<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Http\Request;

class AddMemberSlotController extends Controller
{
    public function store(Workspace $workspace)
    {
    	$subscription = $workspace->subscription;

        // retrieve subscription
    	\Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $stripeSub = \Stripe\Subscription::retrieve($subscription->stripe_id);

        // increment current stripe subscription item (current subscription plan) quantity.
        \Stripe\Subscription::update($stripeSub['id'], [
          'cancel_at_period_end' => false,
          'items' => [
            [
              'id' => $stripeSub['items']['data'][0]['id'],
              'quantity' => $stripeSub['quantity'] + 1,
            ],
          ],
        ]);

        // Create and pay an invoice for added member
        $invoice = \Stripe\Invoice::create([
            "customer" => $stripeSub['customer'],
            "subscription" => $stripeSub['id'],
            "description" => 'Add additional member slot'
        ]);

        $finalizedInvoice = $invoice->finalizeInvoice();

        // Customer is redirected to the stripe Hosted invoice payment page.
        // After the successful payment, Workspace members limit is incremented, as well as WSA.
        return response($finalizedInvoice, 200);
    }
}
