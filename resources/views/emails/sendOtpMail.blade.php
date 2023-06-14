@component('mail::message')

Use This Otp For Verify Account

# {{ $maildata['title'] }}



Thanks,<br>
{{ config('app.name') }}
@endcomponent
