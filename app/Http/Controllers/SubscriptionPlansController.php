<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Plan;

class SubscriptionPlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('subscription-plans.index', ['plans' => Plan::all()]);
    }
}
