<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
