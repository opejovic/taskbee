@extends('layouts.app') @section('content')
<div
    class="flex items-center justify-center text-center mx-auto mt-5 border max-w-lg px-10 py-10 w-full shadow rounded-sm"
>
    <div class="w-full">
        <div class="col-md-8">
            <div class="card">
                <div class="text-xl text-gray-800">
                    Create your workspace, {{ auth()->user()->first_name }}.
                </div>

                <div class="card-body">
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
                                class="mt-10 appearance-none block w-full bg-gray-200 text-center text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                name="name"
                                id="name"
                                placeholder="Choose your workspace name"
                                aria-describedby="nameHelp"
                            />
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
</div>
@endsection
