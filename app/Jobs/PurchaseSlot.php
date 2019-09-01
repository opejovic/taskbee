<?php

namespace taskbee\Jobs;

use Illuminate\Bus\Queueable;
use taskbee\Models\Workspace;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use taskbee\Billing\StripeSubscriptionGateway;

class PurchaseSlot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Workspace instance.
     *
     * @var \taskbee\Models\Workspace
     */
    protected $workspace;

    /**
     * Create a new job instance.
     *
     * @param \taskbee\Models\Workspace $workspace
     */
    public function __construct(Workspace $workspace)
    {
        $this->workspace = $workspace;
    }

    /**
     * Execute the job.
     *
     * @param \taskbee\Billing\StripeSubscriptionGateway $subscriptionGateway
     * @return void
     */
    public function handle(StripeSubscriptionGateway $subscriptionGateway)
    {
        $subscriptionGateway->increaseSlot($this->workspace);
    }
}
