<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionPurchaseEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subscription, $invitation)
    {
        $this->subscription = $subscription;
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails/subscription-purchased');
    }
}
