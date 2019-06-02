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
        $subItem = $stripeSub['items']['data'][1];
        \Stripe\SubscriptionItem::update(
            $subItem['id'],
            [
                'quantity' => $subItem['quantity'] + 1,
            ]
        );

        $authorization = WorkspaceSetupAuthorization::where('subscription_id', $subscription->stripe_id)->first();
        $authorization->increment('members_limit');

        Workspace::where('subscription_id', $subscription->stripe_id)->first()->increment('members_limit');

        return response($authorization->code, 200);
    }
}
