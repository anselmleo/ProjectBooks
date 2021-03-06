@component('mail::message')
# Welcome to Fotomi
***

Hi {{ $user->profile->full_name }},

Please verify you email address so we know it's really you!

@component('mail::button', ['url' => env('FRONTEND_URL').$user->verificationToken->token])
    Verify my email address
@endcomponent

Warm Regards,<br>
{{ config('app.name') }}
@endcomponent
