@extends('layouts.app')

@section('content')
    <workspace :workspace="{{ $workspace }}" :invitations="{{ $invitations }}" :tasks="{{ $tasks }}"></workspace>
@endsection

@push('beforeScripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush