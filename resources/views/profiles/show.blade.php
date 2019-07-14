@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
				<avatar-form :profileuser="{{ Auth::user() }}"></avatar-form>
            </div>
        </div>
    </div>
@endsection
