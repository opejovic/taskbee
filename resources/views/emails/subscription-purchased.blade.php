@component('mail::message')
# Hello {{ $authorization->subscription->owner->first_name }},

You have successfully purchased a <strong>{{ $authorization->subscription->plan_name }}</strong> subscription plan.

You can setup your account by visiting the link below.

@component('mail::button', ['url' => "http://127.0.0.1:8000/workspace-setup/{$authorization->code}"])
    Setup workspace
@endcomponent

@component('mail::panel', ['url' => ''])
    In order to use the application, you need to register an account. This link will be valid for only one time. After registration, in case you forget your password, you will have to request a new one.
@endcomponent

Thanks,
<br>
{{ config('app.name') }}
@endcomponent