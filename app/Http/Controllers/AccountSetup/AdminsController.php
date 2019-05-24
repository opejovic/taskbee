<?php

namespace App\Http\Controllers\AccountSetup;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkspaceSetupAuthorization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function store()
    {
        $authorization = WorkspaceSetupAuthorization::findByCode(request('authorization_code'));
        
    	abort_if($authorization->hasBeenUsedForAdmin(), 403);

    	$admin = User::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'role' => User::ADMIN,
            'email_verified_at' => Carbon::now(),
    	]);

    	$authorization->update([
    		'admin_id' => $admin->id,
    		'members_invited' => 1,
    	]);

    	Auth::login($admin);

    	return back();
    }
}
