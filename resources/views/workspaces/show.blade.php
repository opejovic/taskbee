@extends('layouts.app')

@section('content')
<div class="py-5 xl:flex lg:flex md:flex sm:flex w-full">
    <div class="xl:w-1/3 lg:w-1/2 md:w-1/2 sm:w-1/2 xl:mb-0 lg:mb-0 md:mb-0 sm:mb-0 mb-5 w-full mx-auto border pt-5 shadow text-center bg-white rounded">
        {{-- make it a vue component --}}
        @include('workspaces._team')
    </div>

    <div class="md:ml-5 xl:ml-5 sm:ml-5 xl:w-2/3 lg:w-1/2 md:w-1/2 sm:w-1/2 w-full mx-auto border pt-5 shadow text-center bg-white rounded">
        @include('workspaces._tasks')
    </div>
</div>
@endsection

@push('beforeScripts')
<script src="https://js.stripe.com/v3/"></script>
@endpush