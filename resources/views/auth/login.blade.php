@extends('layouts.app') @section('content')
<div class="px-4">
    <header class="flex justify-between items-top py-8">
        <div>
            <a href="{{ route('welcome') }}">
                <img class="block w-40" src="/img/logo.svg" alt="taskbee">
            </a>
        </div>

        <div>
            <a href="/signup"
            class="block text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 border-b-2 border-transparent hover:border-indigo-500">
                Sign Up
            </a>
        </div>
    </header>
    <div class="flex pt-16 items-center w-full">
        <div class="w-full max-w-lg mx-auto">
            <div class="flex flex-wrap -mx-3 mb-3 text-center">
                <div class="w-full md:w-3/3 px-3">
                    <span class="font-semibold text-3xl tracking-tight text-indigo-900"><a
                            href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a></span>
                </div>
            </div>

            <form class="" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="flex flex-wrap -mx-3 mb-1">
                    <div class="w-full md:w-3/3 px-3">
                        <input
                            class="shadow appearance-none block w-full bg-gray-200 text-center text-gray-700 @error('email') border-b border-red-500 @enderror rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                            id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" />

                        @error('email')
                        <p class="text-red-500 text-center text-xs -mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-1">
                    <div class="w-full md:w-3/3 px-3">
                        <input
                            class="shadow appearance-none block w-full text-center bg-gray-200 text-gray-700 @error('password') border-b border-red-500 @enderror rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                            id="password" type="password" name="password" placeholder="Password" />

                        @error('password')
                        <p class="text-red-500 text-center text-xs -mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-1">
                    <div class="w-full md:w-3/3 px-3">
                        <button
                            class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"
                            type="submit">
                            {{ __('Sign in') }}

                            <i class="material-icons align-middle" style="font-size: 1em;">
                                arrow_forward
                            </i>
                        </button>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-1 text-center mt-3">
                    <div class="w-full md:w-3/3 px-3">
                        @if (Route::has('password.request'))
                        <a class="text-gray-600 text-xs" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection