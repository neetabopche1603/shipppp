@component('mail::message')

Hi,

# {{ $maildata['title'] }}



Thanks,<br>
{{ config('app.name') }}
@endcomponent
