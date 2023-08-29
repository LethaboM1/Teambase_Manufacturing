<tr>
    <td>{{$supplier['name']}}</td>
    <td>{{$supplier['contact_name']}}</td>
    <td>{{$supplier['contact_number']}}</td>
    <td>{{$supplier['email']}}</td>
    <td>{{$supplier['address']}}</td>
    <td>
        <a href="#edit_{{$supplier['id']}}" class="btn btn-warning modal-sizes"><i class="fa fa-edit"></i></a>
        <div id='edit_{{$supplier['id']}}' class='modal-block modal-block-lg mfp-hide'>
            <form action="{{url('suppliers/save')}}" method='post' enctype='multipart/form-data'>
                @csrf
                <section class='card'>
                    <x-manufacture.suppliers.view-component :supplierid="$supplier['id']" />
                </section>
            </form>
        </div>
        
    </td>
</tr>