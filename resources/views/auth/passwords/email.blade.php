@extends('layouts.app')

@section('content')
    <div class="mx-3">
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
        <div class="pt-10">
            <div class="flex items-center justify-center text-center">
                <div class="xl:w-2/4 lg:w-2/4 w-4/5">
                    {{-- header --}}
                    <div class=" font-bold text-2xl text-indigo-900 pb-2">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="">
                                <div class="flex flex-wrap mb-1">
                                    <div class="w-full md:w-3/3 px-3">
                                        <input
                                            class="shadow appearance-none block w-full bg-gray-200 text-center text-gray-700 @error('email') border-b border-red-500 @enderror rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                                            id="email" type="email" name="email" placeholder="Email"
                                            value="{{ old('email') }}" autofocus />

                                        @error('email')
                                        <p class="text-red-500 text-center text-xs -mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap mb-1">
                                <div class="w-full md:w-3/3 px-3">
                                    <button
                                        class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded"
                                        type="submit">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection