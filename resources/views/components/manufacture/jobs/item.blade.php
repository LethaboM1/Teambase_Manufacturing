<tr class="pointer" onclick="window.open('{{url("job/{$jobcard['id']}")}}','_self');">
    <td>{{$jobcard['created_at']}}</td>
    <td>{{$jobcard['jobcard_number']}}</td>
    <td>{{$jobcard['site_number']}}</td>
    <td>{{($jobcard['customer_id']>0?($jobcard->customer()->credit?"{$jobcard->customer()->account_number} {$jobcard->customer()->name}":"{$jobcard->customer()->name}"):$jobcard['contractor'])}}</td>
    <td>{{$jobcard['contact_number']}}</td>
    <td>
        
    </td>
</tr>