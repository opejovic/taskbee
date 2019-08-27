<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Workspace;
use taskbee\Billing\StripeSubscriptionGateway;

class AddMemberSlotController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \taskbee\Models\Workspace $workspace
     * @param \taskbee\Billing\StripeSubscriptionGateway $gateway
     * @return \Illuminate\Http\Response
     */
    public function store(Workspace $workspace, StripeSubscriptionGateway $gateway)
    {
        $stripeSubscription = $gateway->increaseSlot($workspace);

        $invoice = $gateway->createInvoice($stripeSubscription);

        # Finalize the invoice, wait for
        # invoice payment succeeded web hook, then update the stripe subscription quantity
        $finalizedInvoice = $invoice->finalizeInvoice();

        # Customer is redirected to the stripe Hosted invoice payment page.
        # After the successful payment, Workspace members limit is incremented, as well as WSA.
        return response($finalizedInvoice, 200);
    }
}
