<x-layout :pageTitle="$page_title" >    
    {{-- <iframe id="updates_here" frameborder="0"></iframe> --}}
    <h2>Utils Page</h2>
    @if (Session::get('alertError'))
            <small class="text-danger">{{ Session::get('alertError') }}</small>
    @endif

    <div class="row">
        <div class="col-md-4">
            <h3>Transfer Dispatch Header Products to Lines (Transactions)</h3>
            <form method="POST" action="{{ url('system/headerproducts/transfer') }}">
                @csrf
                <p>This will transfer existing Dispatch Header Line Products to Transactions Lines to accomodate old data before 2024-03-20 when Headers contained only weighed items.
                    All dispatched items are now captured on Lines (Transactions).
                </p>
                @error('database')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <button class="btn btn-warning"><i class="fa fa-right-left"></i>&nbsp;Transfer</button>
            </form>
        </div>



    </div>    

</x-layout>