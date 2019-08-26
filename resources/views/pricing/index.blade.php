@extends('layouts.app')

@section('content')
<div class="px-3 pb-10">
    <header class="flex justify-between items-center pt-4">
        <div class="flex items-center">
            <div class="pr-4">
                <a href="/" class="flex items-center">
                    <img class="w-12" src="/img/logo.svg" alt="Taskbee" />
                    <p class="pl-1 font-bold text-xl tracking-tighter text-indigo-900">
                        taskbee.
                    </p>
                </a>
            </div>
            <div class="pl-1 mx-auto flex">
                <a href="#"
                    class="block mx-2 text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 border-b-2 border-transparent hover:border-indigo-500">
                    Contact
                </a>
                <a href="{{ route('pricing') }}"
                    class="{{ request()->routeIs('pricing') ? 'text-indigo-500 border-b-2 border-indigo-500' : 'border-transparent' }} block text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 border-b-2 hover:border-indigo-500">
                    Pricing
                </a>
            </div>
        </div>


        <a href="/login"
            class="block text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 border-b-2 border-transparent hover:border-indigo-500">
            Sign in
        </a>
    </header>

    <div class="items-center">
        <div class="lg:flex md:flex justify-center pt-10 pb-10">
            @foreach ($plans as $plan)
            <div class="xl:w-1/4 sm:w-full md:w-full sm:mx-2 sm:mt-2">
                <div class="m-2 rounded shadow bg-gray-100 text-gray-700">
                    <div class="">
                        {{-- Header --}}
                        <div class="py-10 text-center uppercase font-light">
                            {{ $plan->name }}
                        </div>

                        {{-- Body --}}
                        <div class="text-center pb-20">
                            <div class="flex items-baseline justify-center">
                                <div class=" font-bold text-4xl">
                                    $ {{ $plan->amountInEur }}
                                </div>
                                <div class="ml-1 text-sm">
                                    / {{ $plan->interval }}
                                </div>
                            </div>
                            <div class="pt-10">
                                <div class="uppercase pb-2">
                                    <span class="px-8 border-b-2 py-1">Features</span>
                                </div>
                                <div>
                                    {{ $plan->members_limit }} member slots.
                                </div>
                                <div>
                                    Unlimited storage.
                                </div>
                                <div>
                                    And many other features.
                                </div>
                            </div>
                        </div>
                        {{-- End body --}}

                        {{-- Footer--}}
                        <div class="flex items-center justify-center pb-10">
                            <a href="{{ route('signup') }}"
                                class="bg-purple-800 text-white block px-10 py-2 rounded text-sm hover:bg-purple-700">
                                Get started
                            </a>
                        </div>
                        {{-- End --}}

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


@endsection