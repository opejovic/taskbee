@extends('layouts.app') @section('content')
<div class="max-w-full">
                   
    <div class="mt-8 -mb-16 text-right mr-32">
         <a href="/signup" class="block mt-4 text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
			Sign up
		</a>
    </div>
    <div class="flex h-screen h-full w-full items-center justify-center">
        <div class="w-full max-w-lg mx-auto">
            <div class="flex flex-wrap -mx-3 mb-3 text-center">
                <div class="w-full md:w-3/3 px-3">
                    <span
                        class="font-semibold text-3xl tracking-tight text-indigo-900"
                        ><a href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a></span
                    >
                </div>
            </div>

            <form class="" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="flex flex-wrap -mx-3 mb-1">
                    <div class="w-full md:w-3/3 px-3">
                        <input
                            class="shadow appearance-none block w-full bg-gray-200 text-center text-gray-700 border @error('email') border-red-700 @enderror rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                            id="email"
                            type="email"
                            name="email"
                            placeholder="Email"
                            required
                            value="{{ old('email') }}"
                        />

                        @error('email')
                        <p class="text-red-600 text-xs -mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-1">
                    <div class="w-full md:w-3/3 px-3">
                        <input
                            class="shadow appearance-none block w-full text-center bg-gray-200 text-gray-700 border @error('password') border-red-700 @enderror rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Password"
                            required
                        />

                        @error('password')
                        <p class="text-red-600 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-1">
                    <div class="w-full md:w-3/3 px-3">
                        <button
                            class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"
                            type="submit"
                        >
                            {{ __('Sign in') }}

                            <i
                                class="material-icons align-middle"
                                style="font-size: 1em;"
                            >
                                arrow_forward
                            </i>
                        </button>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-1 text-center mt-3">
                    <div class="w-full md:w-3/3 px-3">
                        @if (Route::has('password.request'))
                        <a
                            class="text-gray-600 text-xs"
                            href="{{ route('password.request') }}"
                        >
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
