<?php

namespace taskbee\Billing;

use taskbee\Models\Plan;

interface PaymentGateway
{
    /**
     * Charge the customer for the selected subscription plan.
     *
     * @param \taskbee\Models\Plan $plan
     * @return mixed
     */
    public function checkout(Plan $plan);
}
