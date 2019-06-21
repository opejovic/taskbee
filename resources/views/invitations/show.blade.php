@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                @if (App\Models\User::where('email', $invitation->email)->exists())
                    @include('invitations.existing-users')
                @else

                    <div class="card">
                        <div class="card-header">Register your account for the  
                            <strong>{{ $invitation->workspace->name }}</strong> workspace
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('invitees.register') }}">
                                @csrf
                                
                                <input type="hidden" name="invitation_code" value="{{ $invitation->code }}">
                                
                                <div class="form-group">
                                    <label for="email">Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name">

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" placeholder="Enter your last name" value="{{ old('last_name') }}">

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" aria-describedby="emailHelp" placeholder="{{ $invitation->email }}" value="{{ old('email') }}">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email_confirmation" id="email_confirmation" aria-describedby="emailHelp" placeholder="Confirm email address" value="{{ old('email_confirmation') }}">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>

                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password">
                                    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password_confirmation" id="password" placeholder="Confirm password">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                @endif
        </div>
    </div>
</div>
@endsection