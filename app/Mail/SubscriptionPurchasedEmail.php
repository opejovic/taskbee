<?php

namespace taskbee\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionPurchasedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $authorization;

    /**
     * Create a new message instance.
     *
     * @param $authorization
     */
    public function __construct($authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.subscription-purchased')
            ->subject("Successful subscription at TaskBee!");
    }
}
