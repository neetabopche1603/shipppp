@component('mail::message')

Login Details :

# {{ $maildata['email'] }}

# {{ $maildata['password'] }}

@component('mail::button', ['url' => $maildata['url']])

Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
