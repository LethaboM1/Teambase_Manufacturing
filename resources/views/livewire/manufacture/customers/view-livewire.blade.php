<div class="row">
    <div class="col-sm-12 col-md-12 pb-sm-12 pb-md-0">
        <button href="#modalAddCustomer" class="mb-1 mt-1 mr-1 modal-sizes btn btn-primary">Add Customer</button>
        <div class="header-right">
            <div class="input-group">
                <input type="text" class="form-control" wire:model.600ms="search" name="search"
                    placeholder="Search Customer...">
                <button class="btn btn-default" id='searchBtn' type="button"><i class="bx bx-search"></i></button>
            </div>
        </div>
    </div>
    <!-- Modal Create Customer -->
    <div id="modalAddCustomer" class="modal-block modal-block-lg mfp-hide">
        <form method="post" action="{{ url('customers/add') }}" enctype="multipart/form-data">
            @csrf
            <section class="card">
                {{-- <x-manufacture.customers.view-component /> --}}
                <livewire:manufacture.customers.view.customer-view>
            </section>
        </form>
    </div>
    <!-- Modal Create Customer End -->
    <br>
    <br>
    <div class="col-lg-12 mb-12">
        <section class="card">
            <header class="card-header">
                <h2 class="card-title">Manage Customers</h2>
            </header>
            <div class="card-body">
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Account No.</th>
                            <th>Contact</th>
                            <th>Number</th>
                            <th>E-mail</th>
                            <th>Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    @if ($customers->count() > 0)
                        @foreach ($customers as $customer)
                            <livewire:manufacture.customers.list-item-livewire :customer="$customer" />
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No customers...</td>
                        </tr>
                    @endif
                </table>
                {{ $customers->links() }}
            </div>
        </section>
    </div>

</div>
