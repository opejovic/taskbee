<?php

namespace taskbee\Http\Controllers;

class TaskbeeController extends Controller
{
    /**
     * Show the applications landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');
    }
}
