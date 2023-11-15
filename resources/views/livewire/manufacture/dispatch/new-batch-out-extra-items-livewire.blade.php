
<tr>   
    <td>{{$extraitem['the_date']}}</td>
    <td>{{$extraitem['the_description']}}</td>
    <td>{{$extraitem['the_unit']}}</td>
    <td>{{$extraitem['the_qty']}}</td>
    <td>
        @if($dispatchaction == 'new')
            <a wire:click="removeExtraItem('{{$extraitem['id']}}')" class="btn btn-primary btn-sm" title="Remove Item">
                <i class="fas fa-minus"></i>                    
            </a>
        @endif                                                                                                                   
    </td>
</tr>