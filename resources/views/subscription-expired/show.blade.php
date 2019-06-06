@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Subscription Expired</div>

                <div class="card-body">
                    <renew-subscription :workspace="{{ $workspace }}"></renew-subscription>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('beforeScripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush