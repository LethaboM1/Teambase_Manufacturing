<tr>
    <td>
        <x-form.checkbox label="" name="check" :value="1" :toggle="$check" />
    </td>
    <td>
        @if($check)            
            <x-form.number step="0.001" max="{{($batch->qty > $jobcardproduct->qty_due?$jobcardproduct->qty_due:$batch->qty)}}" name="qty" />
        @endif
    </td>
    <td>{{$jobcardproduct->qty_due}}</td>
    <td>{{$jobcard['jobcard_number']}}</td>
    <td>{{$jobcard['contractor']}}</td>
    <td>{{$jobcardproduct->product()->code}} - {{$jobcardproduct->product()->description}}</td>
</tr>