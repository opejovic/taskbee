<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavTestController extends Controller
{
	public function show()
	{
		return view('nav.testnav');
	}
}
