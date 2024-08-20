Good Day {{$recipient_name}},

{{$body}}

@foreach ($links as $link)
    {{$link['url']}}
@endforeach

Kind Regards,
{{$sender_name}}