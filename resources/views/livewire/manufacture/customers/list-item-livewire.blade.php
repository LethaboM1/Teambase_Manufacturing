<tr class="pointer">
    <td onclick="$('#btn-edit-{{$customer['id']}}').click()">{{$customer['name']}}</td>
    <td onclick="$('#btn-edit-{{$customer['id']}}').click()">{{$customer['account_number']}}</td>
    <td onclick="$('#btn-edit-{{$customer['id']}}').click()">{{$customer['contact_name']}}</td>
    <td onclick="$('#btn-edit-{{$customer['id']}}').click()">{{$customer['contact_number']}}</td>
    <td onclick="$('#btn-edit-{{$customer['id']}}').click()">{{$customer['email']}}</td>
    <td onclick="$('#btn-edit-{{$customer['id']}}').click()">{{$customer['address']}}</td>
    <td>
        @if (Auth::user()->getSec()->getCRUD('customer_crud')['update'] || Auth::user()->getSec()->global_admin_value)        
            <a class="mb-1 mt-1 mr-1 modal-basic" id='btn-edit-{{$customer['id']}}' href="#edit_{{$customer['id']}}" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Edit Customer"><i class="fas fa-pencil-alt"></i></a>
            {{-- <a id='btn-edit-{{$customer['id']}}' href="#edit_{{$customer['id']}}" class="btn btn-warning modal-sizes"><i class="fa fa-edit"></i></a> --}}
            <div id='edit_{{$customer['id']}}' class='modal-block modal-block-lg mfp-hide'>
                <form action="{{url('customers/save')}}" method='post' enctype='multipart/form-data'>
                    @csrf
                    <section class='card'>
                        {{-- <x-manufacture.customers.view-component :customerid="$customer['id']" /> --}}
                        <livewire:manufacture.customers.view.customer-view  :customerid="$customer['id']" >
                    </section>
                </form>
            </div>
        @endif
        
    </td>
</tr>