<?php

namespace App\Billing;

interface SubscriptionGateway
{
    public function fulfill($purchase);
}
