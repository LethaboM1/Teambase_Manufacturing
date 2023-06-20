<tr>
    <td>{!! ($flag?"<i class='fa fa-times text-danger'></i>&nbsp({$flag} {$product->product()->unit_measure}) short":"<i class='fa fa-check text-success'></i>") !!}</td>
    <td>{{$qty}}</td>
    <td>{{$in_stock}}</td>
    <td>{{$product->product()->code}}</td>
    <td>{{$product->product()->description}}</td>
</tr>