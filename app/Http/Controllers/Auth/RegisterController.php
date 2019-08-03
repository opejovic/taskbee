<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\User
     */
    protected function register()
    {
        request()->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'confirmed', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'email_verified_at' => Carbon::now(),
		]);

        Auth::login($user);

		return response('created', 200);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\User
     */
    protected function registerInvitees()
    {
        $invitation = Invitation::findByCode(request('invitation_code'));

        abort_if($invitation->hasBeenUsed(), 403);

        request()->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'confirmed', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'role' => User::MEMBER,
            'workspace_id' => $invitation->workspace_id,
            'email_verified_at' => Carbon::now(),
        ]);

        $invitation->update([
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ]);

        $invitation->workspace->addMember($user);

        Auth::login($user);

        return redirect(route('tasks.index', $user->workspace_id));
    }
}
