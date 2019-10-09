@component('mail::message')
Hello {{$data['name']}} !

Your Hobby Titled : {{$data['hobby_title']}}, have been successfully {{$data['hobby_action']}}

Thanks<br>
@endcomponent
