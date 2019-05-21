@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Invite members to your workspace, {{ auth()->user()->first_name }} san.
                    <small class="form-text text-muted">You can invite up to {{ $authorization->members_limit }} members. ({{ $authorization->members_limit - $authorization->members_invited  }} remaining)</small></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('invite-members', $authorization->workspace_id) }}">
                        @csrf
                        <input type="hidden" name="authorization_code" value="{{ $authorization->code }}">

                        <div class="form-group">
                            <label for="first_name">First name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter members first name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter members last name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share this email with anyone else.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
