<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Plan;

class PricingController extends Controller
{
    /**
     * Show pricing plans.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pricing.index', ['plans' => Plan::all()]);
    }
}
