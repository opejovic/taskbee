@component('mail::message')
# Hello {{ $authorization->subscription->owner->first_name }},

You have successfully purchased a <strong>{{ $authorization->subscription->plan_name }}</strong> subscription plan.

You can setup your account by visiting the link below.

@component('mail::button', ['url' => config('app.url') . "/workspace-setup/{$authorization->code}"])
Setup workspace
@endcomponent

@component('mail::panel', ['url' => ''])
In order to use the application, you need to create a workspace and invite team members.
@endcomponent

Thanks,
<br>
{{ config('app.name') }}
@endcomponent