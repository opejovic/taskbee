<?php

namespace taskbee\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionExpiredEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The subscription instance.
     *
     * @var \App\Models\Subscription
     */
    public $subscription;

    /**
     * Create a new message instance.
     *
     * @param $subscription
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.subscription-expired')
            ->subject('Your subscription at TaskBee has expired.');
    }
}
