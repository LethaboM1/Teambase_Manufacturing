<tr>
    <td>
        <x-form.checkbox label="" name="check" :value="1" :toggle="$check" />
    </td>
    <td>{{$jobcard['jobcard_number']}}</td>
    <td>{{$jobcard['contractor']}}</td>
    <td>{{$product['code']}} - {{$product['description']}}</td>
    <td>{{$qty}}</td>
</tr>