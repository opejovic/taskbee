@extends('layouts.app')

@section('content')
    <div>
        <div>
			<sign-up-form :plans="{{ $plans }}"></sign-up-form>
        </div>
	</div>
@endsection

@push('beforeScripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush