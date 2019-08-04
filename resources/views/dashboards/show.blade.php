@extends('layouts.app')

@section('content')
<div class="py-5 px-2">
    {{ $workspaces->count() }}
    @forelse($workspaces as $workspace)
    <div>
        <a href="{{ route('workspaces.show', $workspace) }}">{{ $workspace->name }}</a>
    </div>
    @empty
    <p>Nothing here.</p>
    @endforelse
</div>
@endsection