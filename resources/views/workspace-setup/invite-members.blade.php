@extends('layouts.app')

@section('content')
<div
    class="flex items-center justify-center mx-auto mt-5 border max-w-lg px-10 py-5 w-full shadow rounded-sm bg-white">
    <div class="w-full">
        <img class="w-64 mx-auto" src="/img/invite-members.svg" alt="">
        <div class="w-full text-center pt-10">
            <div class="text-xl text-gray-800">
                Invite members to your workspace,
                {{ auth()->user()->first_name }}.
            </div>
            <small class="text-xs text-gray-700">
                You can invite up to
                {{ $authorization->members_limit - 1 }} members. ({{ $authorization->invites_remaining  }}
                remaining)
            </small>

            <div class="pt-5">
                <form method="POST" action="{{ route('invite-members', $authorization->workspace_id) }}">
                    @csrf
                    <input type="hidden" name="authorization_code" value="{{ $authorization->code }}" />

                    <div class="-mx-3 mb-3">
                        <div class="w-full px-3">
                            <input type="text"
                                class="text-center @error('first_name') border-red-700 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="first_name" id="first_name" placeholder="First name" required />

                            @error('first_name')
                            <p class="text-red-600 text-xs">
                                <strong>{{ $message }}</strong>
                            </p>
                            @enderror
                        </div>
                    </div>
                    <div class="-mx-3 mb-3">
                        <div class="w-full md:w-3/3 px-3">
                            <input type="text"
                                class="text-center @error('last_name') border-red-700 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="last_name" id="last_name" placeholder="Last name" required />

                            @error('last_name')
                            <p class="text-red-600 text-xs">
                                <strong>{{ $message }}</strong>
                            </p>
                            @enderror
                        </div>
                    </div>
                    <div class="-mx-3 mb-3">
                        <div class="w-full md:w-3/3 px-3">
                            <input type="email"
                                class="text-center @error('email') border-red-700 @enderror appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                                name="email" id="email" aria-describedby="emailHelp" placeholder="Email" required />

                            @error('email')
                            <p class="text-red-600 text-xs">
                                <strong>{{ $message }}</strong>
                            </p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex pt-5">
                        <button type="submit"
                            class="mr-1 flex-1 uppercase shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                            Invite
                        </button>
                        <a class="text-center ml-1 -mr-1 uppercase shadow bg-gray-500 hover:bg-gray-400 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"
                            href="{{ route('workspaces.show', $authorization->workspace_id) }}">Finish</a>
                    </div>
                    <div class="pt-5 text-xs text-gray-700 text-center">
                        You can come back later to it.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection