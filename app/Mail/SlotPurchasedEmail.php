<?php

namespace taskbee\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SlotPurchasedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The workspace instance.
     *
     * @var \App\Models\Workspace
     */
    public $workspace;
    
    /**
     * The worskapce setup authorization instance.
     *
     * @var \App\Models\WorkspaceSetupAuthorization
     */
    public $authorization;

    /**
     * Create a new message instance.
     *
     * @param $workspace
     * @param $authorization
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
            ->subject('Successful purchase of additional member slot at TaskBee.');
    }
}
