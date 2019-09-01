<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Workspace;
use taskbee\Jobs\PurchaseSlot;

class AddMemberSlotController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \taskbee\Models\Workspace $workspace
     * @return \Illuminate\Http\Response
     */
    public function store(Workspace $workspace)
    {
        PurchaseSlot::dispatch($workspace);

        return response(['Invoice will be sent shortly to your email.'], 200);
    }
}
