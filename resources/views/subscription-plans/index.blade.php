@extends('layouts.app')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Pricing</h1>
        <p class="lead">Lorem ipsum dolor sit</p>
    </div>

    <div class="container">
        <div class="row justify-content-center text-center">
            @foreach($plans as $plan)
                <div class="col-md-3">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">{{ $plan->name }}</h4>
                        </div>

                        <div class="card-body">
                            Members limit: {{ $plan->members_limit }}
                            <hr>
                            <div class="display-4">
                                € {{ $plan->amountInEur }}
                            </div>
                            per month
                        </div>

                        <div class="card-footer">
                            @auth
                                <subscription-checkout :plan="{{ $plan }}"></subscription-checkout>
                            @else
                                <a href="{{ route('login') }}">Sign in</a> or <a
                                    href="{{ route('register') }}">Register</a> in order to purchase the bundle.
                            @endauth
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
