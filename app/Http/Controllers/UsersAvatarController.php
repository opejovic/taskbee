<?php

namespace taskbee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersAvatarController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		request()->validate([
			'avatar' => [
				'required',
				'image',
				'dimensions:max_width=750,max_height=750'
			],
		], ['avatar.dimensions' => 'The maximum avatar dimensions are 750x750.']);

		Auth::user()->update([
			'avatar_path' => request()->file('avatar')->store('avatars', 'public')
		]);

		return back();
	}
}
