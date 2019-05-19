<?php

namespace App\Listeners;

use App\Events\SubscriptionPurchased;
use App\Facades\AuthorizationCode;
use App\Models\User;
use App\Models\WorkspaceSetupAuthorization;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSubscriptionPurchasedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscriptionPurchased  $event
     * @return void
     */
    public function handle(SubscriptionPurchased $event)
    {
        WorkspaceSetupAuthorization::create([
            'email' => $event->subscription->email,
            'user_role' => User::ADMIN,
            'code' => AuthorizationCode::generate(),
            'subscription_id'=> $event->subscription->id,
            'plan_id'=> $event->subscription->plan_id,
        ])->send($event->subscription);
    }
}
