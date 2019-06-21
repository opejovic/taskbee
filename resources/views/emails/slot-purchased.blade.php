@component('mail::message')
# Hello {{ $workspace->subscription->owner->first_name }},

You have successfully purchased additional member slot for <strong>{{ $workspace->name }}</strong> workspace.

You can send an invitation to an additional user by visiting the link below.

@component('mail::button', ['url' => "http://127.0.0.1:8000/workspace-setup/{$authorization->code}"])
    Invite member
@endcomponent

Thanks,
<br>
{{ config('app.name') }}
@endcomponent