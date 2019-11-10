@component('mail::message')
# Order Posted To Fotomi
***

Hey {{ $user->profile->first_name }},

Thanks for using Fotomi
Your new order has been posted successfully!

Warm Regards,<br>
{{ config('app.name') }}
@endcomponent
