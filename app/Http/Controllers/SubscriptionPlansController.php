<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Plan;
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
    	$plans = Plan::all()->except([
            Plan::whereName("Per User Monthly")->first()->id, 
            Plan::whereName("Base Fee")->first()->id
        ]);

		return view('subscription-plans.index', ['plans' => $plans]);
    }
}
