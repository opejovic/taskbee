@extends('layouts.app')

@section('content')
    <div>
        <div>
				<sign-up :plans="{{ $plans }}"></sign-up>
        </div>
	</div>

	
@endsection

@push('beforeScripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush