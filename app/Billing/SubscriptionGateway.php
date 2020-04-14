<?php

namespace taskbee\Billing;

interface SubscriptionGateway
{
    /**
     * Fulfills the process of subscribing the customer after a successful payment.
     *
     * @param array $purchase
     *
     * @return void
     */
    public function fulfill($purchase) : void;
}
