<?php

namespace taskbee\Http\Controllers;

use Illuminate\Http\Request;

class NewDesignController extends Controller
{
    public function index()
    {
        return view('newdesign.index');
    }
}