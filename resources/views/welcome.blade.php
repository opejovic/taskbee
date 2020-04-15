@extends('layouts.app')

@section('content')
<div class="px-3 pb-10">
    <header class="flex justify-between items-center pt-4">
        <div class="flex items-center">
            <div class="pr-4">
                <a href="#" class="flex items-center">
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
                    class="block text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 border-b-2 border-transparent hover:border-indigo-500">
                    Pricing
                </a>
            </div>
        </div>

        <a href="/login"
            class="block text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 border-b-2 border-transparent hover:border-indigo-500">
            Sign in
        </a>
    </header>
    <div class="lg:flex md:flex flex-wrap md:pb-10 items-center pt-10 justify-between w-full">
        {{-- left section --}}
        <div style="position: relative !important;" class="flex-row xl:w-2/5 lg:w-1/3 md:w-1/2 w-5/6 welcome-card-2">
            <div class="shadow bg-indigo-800 rounded overflow-hidden w-full p-4">
                <div class="pb-4 pt-5  text-4xl text-white tracking-tight font-semibold text-center">
                    <p>taskbee.</p>
                    <p class="px-4 -mt-3 text-sm text-white tracking-loose font-light text-center">
                        A Team To-Do List App
                    </p>
                </div>
                <div class="px-10 pt-5 pb-5 bg-indigo-800">
                    <p class="text-white tracking-tight text-2xl font-semibold text-center">
                        Create a workspace for your team, and start assigning tasks today.
                    </p>
                    <p class="text-white tracking-tight text-2xl font-light text-center pt-5">
                        How do you keep track of 30, 40 or 100 team-tasks? It's simple.
                    </p>
                </div>
                <div class="py-8 text-center justify-center bg-indigo-800">
                    <a href="{{ route('signup') }}"
                        class="bg-green-400 px-12 py-3 mx-1 text-white border border-indigo-800 rounded hover:bg-green-500">
                        Subscribe
                    </a>
                </div>
            </div>
            <h1 class="text-center pt-2 font-mono font-normal text-xs text-gray-800">The first 14 days are on us.</h1>
        </div>

        {{-- right section --}}
        <div class="xl:w-3/6 lg:w-1/3 md:w-1/2 w-5/6 flex items-center px-4 xl:pt-24 lg:pt-32 pb-10">
            <img style="position: relative !important;" src="img/welcome.svg" alt="Team">
            <img class="welcome-cat" style="position: absolute !important; z-index: -1; top: 20%;" width="75" src="img/playful-cat.svg" alt="Team">
        </div>

    </div>
</div>
@endsection