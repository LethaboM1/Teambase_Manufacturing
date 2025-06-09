<html>

    <p>Good Day {{$recipient_name}}, </p>

    <p>{{$body}}</p>
    <br>
    @foreach ($links as $link)      
        <a href={{$link['url']}}>{{$link['description']}}</a>        
    @endforeach

    <p>Kind Regards,</p>
    <p>{{$sender_name}}</p>
    <img src="{{url('img/logos/teambase_logo_long.png')}}" alt="Teambase Logo">
    
</html>