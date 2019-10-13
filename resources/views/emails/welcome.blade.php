@component('mail::message')
# Welcome to Fotomi
***

There {{ $user->profile->first_name }},

Welcome to Fotomi
Experience imagery and photo framing like you never did before.

Warm Regards,<br>
{{ config('app.name') }}
@endcomponent
