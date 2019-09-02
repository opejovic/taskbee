<?php

namespace taskbee\Http\Controllers;

use taskbee\Billing\StripeWebhookGateway;

class WebhookController extends Controller
{
    /**
     * Handle stripe web hooks.
     */
    public function handle(StripeWebhookGateway $webhookGateway)
    {
        $webhookGateway->handleEvents();
    }
}
