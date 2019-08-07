<?php

namespace taskbee\Billing;

interface SubscriptionGateway
{
    public function fulfill($purchase);
}
