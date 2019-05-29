@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <subscription-checkout :plan="{{ $plan }}"></subscription-checkout>
        @foreach($members as $member)
            <div class="col-md-2">
                <div class="card">
                    <div class="card-header">{{ $member->full_name }}</div>

                    <div class="card-body">
                        Some data about the user
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('beforeScripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush