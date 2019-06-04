@component('mail::message')
# Introduction

Dear {{ $subscription->owner->first_name }}, your {{ $subscription->plan->name }} subscription is expired.

Please renew it by clicking on the link below.

{{-- here he clicks on the link for renewal, - new stripe checkout session where we pass in the subscription --}}
@component('mail::button', ['url' => ''])
Renew Subscription
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
