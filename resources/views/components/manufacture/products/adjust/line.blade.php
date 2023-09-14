<tr>
    <td>{{$line->created_at}}</td>
    <td>{{$line->qty}}</td>
    <td>{{(isset($user->name)?$user->name:'')}} {{(isset($user->last_name)?$user->last_name:'')}}</td>
    <td>{{$line->comment}}</td>
    <td></td>
</tr>