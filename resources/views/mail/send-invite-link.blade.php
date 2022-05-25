@component('mail::message')
# Hello, {{$user->email}}

Your are invite to manage the Blog,

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
