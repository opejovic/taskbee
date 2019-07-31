@extends('layouts.app')

@section('content')
<div class="lg:flex flex-wrap md:pb-10 items-center justify-center pt-20 px-10">
    {{-- left section --}}
    <div class="xl:w-1/2 lg:w-1/2 md:w-full sm:w-full px-4 mr-5 -ml-5 xl:pt-20 lg:pt-10">
        <img src="img/team.svg" alt="Team">
    </div>

    {{-- right section --}}
    <div class="flex-row xl:w-1/2 lg:w-1/2 md:w-full sm:w-full md:mt-10 rounded-lg overflow-hidden shadow bg-purple-800">
        <div class="border-b border-white pb-5 pt-5 px-4 text-4xl text-white tracking-tight font-semibold text-center">
            taskbee.
        </div>
        <div class="px-10 pt-5 pb-5 bg-purple-800">
            <p class="text-white tracking-tight text-4xl font-semibold text-center">
                Create a workspace for your team, and start assigning tasks today.
            </p>
            <p class="text-white tracking-tight text-2xl font-light text-center pt-5">
                How do you keep track of 30, 40 or 100 team-tasks? It's simple.
            </p>
        </div>
        <div class="pb-5 pt-5 flex items-center justify-center border-t border-white bg-gray-300">
            <a href="{{ route('login') }}"
                class="px-12 py-3 mx-1 text-purple-800  uppercase rounded border border-purple-800 hover:bg-purple-800 hover:text-white">
                Sign In
            </a>
            <a href="{{ route('signup') }}"
                class="bg-purple-800 px-12 py-3 text-gray-300 mx-1 text-white border border-purple-800 uppercase rounded hover:bg-purple-900">
                Sign Up
            </a>
        </div>
    </div>
</div>
@endsection