@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-center">
    	@foreach($bundles as $bundle)
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                	<strong>{{ $bundle->name }}</strong>
                </div>

                <div class="card-body">
                	Members limit: {{ $bundle->members_limit }}
            		<hr>
            		<div class="display-4">
						$ {{ $bundle->price / 100 }}
            		</div>
                    Monthly
                </div>

                <div class="card-footer">
                	<bundle-checkout :bundle="{{ $bundle }}"></bundle-checkout>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('beforeScripts')
	<script src="https://checkout.stripe.com/checkout.js"></script>
@endpush