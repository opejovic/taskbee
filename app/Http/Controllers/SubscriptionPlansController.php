<?php

namespace taskbee\Http\Controllers;

use taskbee\Models\Plan;
use Illuminate\Http\Request;

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
