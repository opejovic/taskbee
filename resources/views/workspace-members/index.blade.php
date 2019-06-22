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