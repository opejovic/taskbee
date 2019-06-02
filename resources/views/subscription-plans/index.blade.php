@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-center">
    	@foreach($plans as $plan)
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                	<strong>{{ $plan->name }}</strong>
                </div>

                <div class="card-body">
                	Members limit: {{ $plan->members_limit }}
            		<hr>
            		<div class="display-4">
						€ {{ number_format(($plan->amount * $plan->members_limit / 100) + 10, 2) }}
            		</div>
                    Monthly
                </div>

                <div class="card-footer">
                	{{-- <bundle-checkout :plan="{{ $plan }}"></bundle-checkout> --}}
                    @auth
                        <subscription-checkout :plan="{{ $plan }}"></subscription-checkout>
                    @else
                        <a href="">Sign in</a> or <a href="">Register</a> in order to purchase the bundle. 
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