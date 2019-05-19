@component('mail::message')
# Hello {{ $subscription->first_name }},

You have successfully purchased a TaskMonkey <strong>{{ $subscription->bundle_name }}</strong> plan.

You can setup your account by visiting the link below.

@component('mail::button', ['url' => "http://127.0.0.1:8000/invitations/$setupAuthorization->code"])
    Setup workspace
@endcomponent

@component('mail::panel', ['url' => ''])
    In order to use the application, you need to register an account. This link will be valid for only one time. After registration, in case you forget your password, you will have to request a new one.
@endcomponent

Thanks,
<br>
{{ config('app.name') }}
@endcomponent