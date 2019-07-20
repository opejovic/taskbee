@extends('layouts.app') @section('content')
<div class="flex h-screen w-full mx-auto h-full items-center block max-w-2xl">
    <div class="mx-auto w-full">
        <div class="mb-32 -mt-32 text-left">
            <div class="w-full md:w-3/3 px-3">
                <span
                    class="font-semibold text-xl tracking-tight text-indigo-900"
                    ><a href="">taskmonkey.</a></span
                >
            </div>
        </div>
        <div class="flex w-full mx-auto">
            <div class="w-full mx-auto px-3">
                <div class=" -mx-3 mb-3 text-center">
                    <div class="w-full md:w-3/3 px-3">
                        <span
                            class="text-sm uppercase tracking-tight text-indigo-900"
                            ><a href="{{ route('home') }}">Choose a plan</a></span
                        >
                    </div>
                </div>

                <form class="" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class=" -mx-3 mb-1">
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
                        </div>
                    </div>
                    <div class=" -mx-3 mb-1">
                        <div class="w-full md:w-3/3 px-3">
                            <input
                                class="shadow appearance-none block w-full text-center bg-gray-200 text-gray-700 border @error('password') border-red-700 @enderror rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Password"
                                required
                            />
                        </div>
                    </div>

                    <div class=" -mx-3 mb-1">
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
                    <div class=" -mx-3 mb-1 text-center mt-3">
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
            {{-- section two --}}
            <div class="w-full mx-auto">
                <div class=" -mx-3 mb-3 text-center">
                    <div class="w-full md:w-3/3 px-3">
                        <span
                            class="text-sm uppercase tracking-tight text-indigo-900"
                            ><a href="{{ route('home') }}">Acount details</a></span
                        >
                    </div>
                </div>

                <form class="" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class=" -mx-3 mb-1">
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
                        </div>
                    </div>
                    <div class=" -mx-3 mb-1">
                        <div class="w-full md:w-3/3 px-3">
                            <input
                                class="shadow appearance-none block w-full text-center bg-gray-200 text-gray-700 border @error('password') border-red-700 @enderror rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Password"
                                required
                            />
                        </div>
                    </div>

                    <div class=" -mx-3 mb-1">
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
                    <div class=" -mx-3 mb-1 text-center mt-3">
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
</div>
@endsection
