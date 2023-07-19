<tr class="pointer" onclick="window.open('{{url("dispatch/{$batch['id']}")}}','_self');">
    <td>{{$batch['created_at']}}</td>
    <td>{{$batch['batch_number']}}</td>
    <td>{{$product}}</td>
    <td>{{$batch['qty']}}</td>
    <td>{{$batch['status']}}</td>
    <td>
        
    </td>
</tr>