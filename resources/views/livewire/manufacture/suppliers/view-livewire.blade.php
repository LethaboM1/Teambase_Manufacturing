<div class="row">
    <div class="col-sm-12 col-md-12 pb-sm-12 pb-md-0">
        <button href="#modalAddSupplier" class="mb-1 mt-1 mr-1 modal-sizes btn btn-primary">Add Supplier</button>
        <div class="header-right">
            <div class="input-group">
                <input type="text" class="form-control" wire:model.600ms="search" name="search"  placeholder="Search Supplier...">
                <button class="btn btn-default" id='searchBtn' type="button"><i class="bx bx-search"></i></button>                    
            </div>
        </div>
    </div>
    <!-- Modal Create Supplier -->
    <div id="modalAddSupplier" class="modal-block modal-block-lg mfp-hide">
        <form method="post" action="{{url('suppliers/add')}}" enctype="multipart/form-data">
            @csrf
            <section class="card">
                <x-manufacture.suppliers.view-component />
            </section>
        </form>
    </div>
    <!-- Modal Create Supplier End -->
    <br>
    <br>
    <div class="col-lg-12 mb-12">
        <section class="card">
            <header class="card-header">
                <h2 class="card-title">Manage Suppliers</h2>
            </header>
            <div class="card-body">
            <table class="table table-hover table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Number</th>
                        <th>E-mail</th>
                        <th>Address</th>
                        <th></th>
                    </tr>
                </thead>
                @if($suppliers->count()>0)
                    @foreach($suppliers as $supplier)
                        <livewire:manufacture.suppliers.list-item-livewire :supplier="$supplier" />
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No suppliers...</td>
                    </tr>
                @endif
            </table>
            </div>
        </section>
    </div>
    
</div>
