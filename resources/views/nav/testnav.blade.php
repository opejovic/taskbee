@extends('layouts.app') @section('content')
    <div class=" -mx-3 mb-3">
        <div class="w-full md:w-3/3 px-3">
            <p class="text-xs tracking-tight text-indigo-900">
                What is the task?
            </p>
            <input
                type="text"
                class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:border-indigo-600 focus:bg-white"
                name="name"
                id="name"
                required
            />
        </div>
    </div>

    <div class="py-4">
        <p class="text-xs tracking-tight text-indigo-900">
            Member responsible will be notified via email.
        </p>
        <div class="flex w-full text-right">
            <button
                type="submit"
                class="flex-1 block uppercase mx-auto shadow  bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"
            >
                Submit
            </button>
        </div>
    </div>
@endsection
