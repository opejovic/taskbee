@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-left">

            @forelse($members as $member)
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-header">{{ $member->full_name }}</div>

                        <div class="card-body">
                            Some data about the user
                        </div>

                        @if(auth()->user()->owns($workspace))
                            @if(auth()->id() !== $member->user_id)
                                <div class="card-footer">
                                    <form action="/workspaces/{{$workspace->id}}/members/{{$member->user_id}}" method="POST">
                                        @method('PATCH')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                    <br>
                </div>
            @empty
                Woooosh, all empty.
            @endforelse

        </div>
        @if (auth()->user()->owns($workspace))
            <member-slot-checkout :workspace="{{ $workspace }}"></member-slot-checkout>
        @endif
    </div>
@endsection

@push('beforeScripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush
