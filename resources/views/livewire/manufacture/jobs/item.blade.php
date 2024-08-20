<tr>
    <td>{{$product['code']}} {{$product['description']}}</td>
    <td>{{$item['qty']}}</td>
    <td>{{$item->qty_due}}</td>
    <td>{!!($item->filled?"<i class='fa fa-check text-success'></i>":"<i class='fa fa-times text-warning'></i>")!!}</td>
    <td>
        @if (Auth::user()->getSec()->getCRUD('jobcards_crud')['create'] || Auth::user()->getSec()->getCRUD('jobcards_crud')['update'] || Auth::user()->getSec()->getCRUD('jobcards_crud')['update'] || Auth::user()->getSec()->global_admin_value)
            @if($item->qty_due==$item->qty)
                <button wire:click="rem_product" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>            
            @endif
        @endif
    </td>
</tr>
