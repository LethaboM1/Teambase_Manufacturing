<tr>
    <td>{{$product['code']}} {{$product['description']}}</td>
    <td>{{$item['qty']}}</td>
    <td>
        <button wire:click="rem_product" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
    </td>
</tr>
