@component('mail::message')
# Hello {{ $invitation->first_name }},

You have been invited to join ChoreBear <strong>{{ $subscription->plan }}</strong> plan.

You can register your account by visiting the link below.

@component('mail::button', ['url' => invitations/])
Setup account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
