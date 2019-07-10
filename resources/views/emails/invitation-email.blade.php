@component('mail::message')
# Hello {{ $invitation->first_name }},

You have been invited to join <strong>{{ $invitation->workspace->name }}</strong> workspace at TaskMonkey.

You can register your account by visiting the link below.

@component('mail::button', ['url' => "http://127.0.0.1:8000/invitations/{$invitation->code}"])
    Accept the invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
