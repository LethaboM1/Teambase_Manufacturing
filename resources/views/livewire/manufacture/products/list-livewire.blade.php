 
    <div class="row">

        @if(auth()->user()->role == 'manager')
            <div class="col-lg-12 mb-3">
                <form method="post" action="products/add" id="addplant">
                    @csrf
                    <section class="card">
                        <header class="card-header">
                            <h2 class="card-title">Add New Product or Recipe</h2>
                        </header>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">                               
                                    <x-form.input wire=0 name="code" label="Product Code" :value="old('code')" />
                                </div>
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">                                
                                    <x-form.input wire=0 name="description" label="Product Description" :value="old('description')" />
                                </div>
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                    <x-form.number wire=0 step="0.1" name="opening_balance" label="Opening Balance" :value="old('opening_balance')" />
                                </div>
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                    <x-form.select wire=0 name="unit_measure" label="Unit Measure" :value="old('unit_measure')" :list="$unit_measure_list" />
                                </div>
                                <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                    {{-- <div class="checkbox-custom checkbox-default">
                                        <input id="checkbox1" name="has_recipe" type="checkbox" value="1">
                                        <label for="checkbox1">Requires recipe</label>
                                    </div> --}}
                                    <x-form.checkbox wire=0 name="has_recipe" label="Has a recipe?" :toggle="old('has_recipe')" :value="1" />
                                </div>
                            </div>
                        </div>
                        <footer class="card-footer text-end">
                            <button class="btn btn-primary">Add Products</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </footer>
                    </section>
                </form>
            </div>
        @endif  
        
       

        
        <div class="col-lg-12 mb-3">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Products</h2>
                </header>
                <div class="card-body">
                    <div class="header-right">
                        <form action="#" class="search nav-form">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" wire:model="search" placeholder="Search Product...">
                                <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <table width="100%" class="table table-responsive-md mb-0">
                        <thead>
                            <tr>
                                <th width="15%">Date</th>
                                <th width="15%">Product Code</th>
                                <th width="40%">Description</th>
                                <th width="15%">Opening Value</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($products_list)>0)
                                @foreach($products_list as $product)
                                    <x-manufacture.products.item :item="$product" />
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No products..</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
