<?php

namespace App\Listeners;

use App\Events\SubscriptionPurchased;
use App\Facades\InvitationCode;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInvitationEmail
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
        Invitation::create([
            'email' => $event->subscription->email,
            'user_role' => User::ADMIN,
            'code'  => InvitationCode::generate(),
            'subscription_id' => $event->subscription->id,
        ])->send($event->subscription);
    }
}
