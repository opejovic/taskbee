<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Plan;
use Illuminate\Http\Request;

class BundlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$plans = Plan::all();
		return view('bundles.index', ['plans' => Plan::all()]);        
    }
}
