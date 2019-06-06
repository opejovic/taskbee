<div class="card">
    <div class="card-header">Accept invitation to  
        <strong>{{ $invitation->workspace->name }}</strong> workspace
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('accept-invitation.store') }}">
	        @csrf

	        <input type="hidden" name="invitation_code" value="{{ $invitation->code }}">

	        <button type="submit" class="btn btn-primary">Accept invitation</button>
    	</form>
    </div>
</div>