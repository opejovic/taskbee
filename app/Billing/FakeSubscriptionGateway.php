<?php 

namespace App\Billing;

use App\Billing\SubscriptionGateway;
use App\Models\Customer;
use App\Models\Subscription;
use Carbon\Carbon;

class FakeSubscriptionGateway implements SubscriptionGateway
{
	/**
	 * summary
	 *
	 * @return void
	 * @author 
	 */
	public function fulfill($purchase)
	{
	    
	}
}
