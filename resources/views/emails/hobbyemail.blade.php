@component('mail::message')
Hello {{$data['name']}}} !

Your Hobby with title {{$data['hobby_title']}} have been successfully {{$data['hobby_action']}}

@endcomponent

Thanks,<br>
@endcomponent
