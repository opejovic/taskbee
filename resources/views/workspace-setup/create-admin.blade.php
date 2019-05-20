@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create your account</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('store-admin') }}">
                        @csrf
                        <input type="hidden" name="authorization_code" value="{{ $authorization->code }}">

                        <div class="form-group">
                            <label for="email">First name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter your first name">
                        </div>
                        <div class="form-group">
                            <label for="email">Last name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter your last name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email_confirmation" id="email-confirm" aria-describedby="emailHelp" placeholder="Confirm email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Confirm password">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
