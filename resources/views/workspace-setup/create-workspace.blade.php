@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create your workspace, {{ auth()->user()->first_name }} san.</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('store-workspace') }}">
                            @csrf
                            <input type="hidden" name="authorization_code" value="{{ $authorization->code }}">

                            <div class="form-group">
                                <label for="name">Workspace name</label>
                                <input type="text" class="form-control" name="name" id="name"
                                       placeholder="Choose your workspace name" aria-describedby="nameHelp">
                                <small id="nameHelp" class="form-text text-muted">Choose wisely.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
