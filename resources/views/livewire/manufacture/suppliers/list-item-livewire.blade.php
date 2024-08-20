<tr class="pointer">
    <td onclick="$('#btn-edit-{{$supplier['id']}}').click()">{{$supplier['name']}}</td>
    <td onclick="$('#btn-edit-{{$supplier['id']}}').click()">{{$supplier['contact_name']}}</td>
    <td onclick="$('#btn-edit-{{$supplier['id']}}').click()">{{$supplier['contact_number']}}</td>
    <td onclick="$('#btn-edit-{{$supplier['id']}}').click()">{{$supplier['email']}}</td>
    <td onclick="$('#btn-edit-{{$supplier['id']}}').click()">{{$supplier['address']}}</td>
    <td>
        @if (Auth::user()->getSec()->getCRUD('supplier_crud')['update'] || Auth::user()->getSec()->global_admin_value) 
            <a class="mb-1 mt-1 mr-1 modal-basic" id='btn-edit-{{$supplier['id']}}' href="#edit_{{$supplier['id']}}" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Edit Supplier"><i class="fas fa-pencil-alt"></i></a>
            {{-- <a id='btn-edit-{{$supplier['id']}}' href="#edit_{{$supplier['id']}}" class="btn btn-warning modal-sizes"><i class="fa fa-edit"></i></a> --}}
            <div id='edit_{{$supplier['id']}}' class='modal-block modal-block-lg mfp-hide'>
                <form action="{{url('suppliers/save')}}" method='post' enctype='multipart/form-data'>
                    @csrf
                    <section class='card'>
                        <x-manufacture.suppliers.view-component :supplierid="$supplier['id']" />
                    </section>
                </form>
            </div>
        @endif
        
    </td>
</tr>