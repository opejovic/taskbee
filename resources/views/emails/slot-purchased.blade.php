@component('mail::message')
# Hello {{ $workspace->subscription->owner->first_name }},

You have successfully purchased additional member slot for <strong>{{ $workspace->name }}</strong> workspace.

You can send an invitation to an additional user by visiting the link below.

@component('mail::button', ['url' => config('app.url') . "/workspace-setup/{$authorization->code}"])
Invite member
@endcomponent

Thanks,
<br>
{{ config('app.name') }}
@endcomponent