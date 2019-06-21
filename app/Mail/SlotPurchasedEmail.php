<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SlotPurchasedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $workspace;
    public $authorization;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($workspace, $authorization)
    {
        $this->workspace = $workspace;
        $this->authorization = $authorization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.slot-purchased')
            ->subject('Successful purchase of additional member slot at Taskmonkey.');
    }
}