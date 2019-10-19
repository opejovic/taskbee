@extends('layouts.app')

@section('content')
<div class="text-4xl font-bold text-purple-900 text-center pt-5"><a href="/">taskbee.</a></div>
<div class="text-xl text-center pt-5 text-gray-600">Register your account for the <strong
    class="text-gray-700">{{ $invitation->workspace->name }}</strong> workspace</div>
    <div class="flex justify-center items-center pb-10">
        <img class="w-2/5 px-5 mr-2" src="/img/accept-invitation.svg" alt="">
        <div
        class="w-2/3 flex text-gray-700 items-center justify-center mt-10 max-w-lg px-10 py-10 border rounded-lg bg-white">
        <div class="row justify-content-center w-full">
            <div class="">
                @if (taskbee\Models\User::where('email', $invitation->email)->exists())
                @include('invitations.existing-users')
                @else

                <div class="card">

                    <div class="card-body">
                        <form method="POST" action="{{ route('invitees.register') }}">
                            @csrf

                            <input type="hidden" name="invitation_code" value="{{ $invitation->code }}">

                            <div class="form-group w-full mb-3">
                                <input type="text"
                                class="text-center @error('first_name') border-red-500 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="first_name" id="first_name" value="{{ old('first_name') }}"
                                placeholder="Enter your first name">

                                @error('first_name')
                                <p class="text-red-500 text-center text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group w-full mb-3">
                                <input type="text"
                                class="text-center @error('last_name') border-red-500 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="last_name" id="last_name" placeholder="Enter your last name"
                                value="{{ old('last_name') }}">

                                @error('last_name')
                                <p class="text-red-500 text-center text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group w-full mb-3">
                                <input type="email"
                                class="text-center @error('email') border-red-500 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="email" id="email" aria-describedby="emailHelp"
                                placeholder="{{ $invitation->email }}" value="{{ old('email') }}">

                                @error('email')
                                <p class="text-red-500 text-center text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group w-full mb-3">
                                <input type="email"
                                class="text-center @error('email') border-red-500 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="email_confirmation" id="email_confirmation" aria-describedby="emailHelp"
                                placeholder="Confirm email address" value="{{ old('email_confirmation') }}">
                            </div>
                            <div class="form-group w-full mb-3">
                                <input type="password"
                                class="text-center @error('password') border-red-500 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="password" id="password" placeholder="Password">

                                @error('password')
                                <p class="text-red-500 text-center text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <input type="password"
                                class="text-center @error('password') border-red-500 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="password_confirmation" id="password" placeholder="Confirm password">
                            </div>

                            <button type="submit"
                            class="mr-1 flex-1 uppercase shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 w-full rounded">Submit</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection