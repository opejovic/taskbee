@extends('layouts.app') 

@section('content')
<div
    class="mx-auto text-center mt-5 border max-w-lg px-10 py-10 w-full shadow rounded-sm bg-white"
>
    <img class="w-64 mx-auto" src="/img/workspace.svg" alt="">
    <div class="w-full">
        <div>
            <div class="text-xl text-gray-800 pt-10">
                Create your workspace, {{ auth()->user()->first_name }}.
            </div>

            <div>
                <form method="POST" action="{{ route('store-workspace') }}">
                    @csrf
                    <input
                        type="hidden"
                        name="authorization_code"
                        value="{{ $authorization->code }}"
                    />

                    <div class="form-group">
                        <input
                            type="text"
                            class="mt-10 appearance-none block w-full bg-gray-300 border @error('name') border-b border-red-500 @enderror text-center text-gray-700 rounded py-4 px-10 mb-3 leading-tight focus:outline-none focus:bg-white"
                            name="name"
                            id="name"
                            placeholder="Choose your workspace name"
                            value="{{ old('name') }}"
                            aria-describedby="nameHelp"
                        />

                        @error('name')
                        <p class="text-red-500 text-center text-xs -mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                            type="submit"
                            class="mt-5 uppercase shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"
                        >
                            Create it
                        </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
