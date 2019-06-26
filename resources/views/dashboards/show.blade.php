@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @forelse($workspaces as $workspace)
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header">{{ $workspace->name }}</div>

                        <div class="card-body">
                            {{ $workspace->creator->full_name }}

                            @forelse($workspace->invitations as $invitation) 
                                <li style="list-style: none;">
                                    {{ $invitation->full_name }}
                                    {{ $invitation->hasBeenUsed() ? 'Accepted' : 'Not accepted' }}
                                </li>
                            @empty
                                <p>Still no invited members</p>
                            @endforelse
                        </div>

                        <div class="card-footer">
                            @if ($workspace->authorization->invites_remaining > 0)
                                You can 
                                    <a href="{{ route('workspace-setup.show', $workspace->authorization->code) }}">invite</a> 
                                    {{ $workspace->authorization->invites_remaining }} more members.
                            @else
                                You have used all your invites. You can buy more at this link.
                            @endif

                        </div>
                    </div>
                </div>
            @empty
                <p>Nothing here.</p>
            @endforelse    
        </div>
    </div>
@endsection
