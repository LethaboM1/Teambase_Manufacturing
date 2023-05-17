 
    <div class="row">
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
        
        <!-- Modal Adjust Product -->
        <div id="modaladjust" class="modal-block modal-block-lg mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Adjust Product</h2>
                </header>
                <div class="card-body">
                    <div class="modal-wrapper">
                        <div class="modal-text">
                            <div class="row">
                                <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Product Code</label>
                                    <input type="text" name="code" placeholder="DB01" class="form-control">
                                </div>
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Product Description</label>
                                    <input type="text" name="disc" placeholder="pen grade bitumen - GENREF"
                                        class="form-control">
                                </div>
                                <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Opening Value</label>
                                    <input type="text" name="openvalue" placeholder="1000" class="form-control">
                                </div>
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Unit</label>
                                <select class="form-control mb-3">
                                    <option>Kg</option>
                                    <option>Tons</option>
                                    <option>Bag</option>
                                    <option>Liters</option>
                                </select>	
                                </div>
                                <hr>
                                <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">New Value</label>
                                    <input type="text" name="openvalue" placeholder="1000" class="form-control">
                                </div>
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Unit</label>
                                <select class="form-control mb-3">
                                    <option>Kg</option>
                                    <option>Tons</option>
                                    <option>Bag</option>
                                    <option>Liters</option>
                                </select>	
                                </div>
                                <div class="col-sm-12 col-md-7 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Reason / Comment</label>
                                    <input type="text" name="disc" placeholder=""
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary">Save Product</button>
                            <button class="btn btn-default modal-dismiss">Cancel</button>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        <!-- Modal adjust Product End -->
        <!-- Modal Recipe -->
        <div id="modaladdrecipe" class="modal-block modal-block-lg mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Add Recipe</h2>
                </header>
                <div class="card-body">
                    <div class="modal-wrapper">
                        <div class="modal-text">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Product Code</label>
                                    <input type="text" name="code" placeholder="DB01" class="form-control">
                                </div>
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Product Description</label>
                                    <input type="text" name="disc" placeholder="pen grade bitumen - GENREF"
                                        class="form-control">
                                </div>
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Opening Value</label>
                                    <input type="text" name="openvalue" placeholder="1000 Tone" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Select Product</label>
                                    <Select class="form-control mb-3">
                                        <option>Product 1</option>
                                        <option>Product 2</option>
                                        <option>Product 3</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Quantity</label>
                                    <input type="text" name="openvalue" placeholder="1000" class="form-control">
                                </div>
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Unit</label>
                                <select class="form-control mb-3">
                                    <option>Kg</option>
                                    <option>Tons</option>
                                    <option>Bag</option>
                                    <option>Liters</option>
                                </select>	
                            </div>
                            </div>
                            <div>
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                    <div>
                                        <button class="btn btn-primary">Add Product</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="card-body">
                                    <h2 class="card-title">Raw Products</h2>
                                </div>
                                <div class="card-body">
                                    <div class="header-right">
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
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="actions">
                                                    <!-- Modal Edit recipe -->
                                                    <a class="mb-1 mt-1 mr-1 modal-basic" href="#modalviewProduct" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Edit Product"><i
                                                        class="fas fa-pencil-alt"></i></a>
                                                    <!-- Modal Edit recipe End -->
                                                    <!-- Modal Delete -->
                                                    <a class="mb-1 mt-1 mr-1 modal-basic" href="#modalHeaderColorDanger" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Delete Product"><i
                                                        class="far fa-trash-alt"></i></a>
                                                    <!-- Modal Delete End -->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary">Add Recipe</button>
                            <button class="btn btn-default modal-dismiss">Cancel</button>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        <!-- Modal recipe End -->
        <!-- Modal Delete -->
        <div id="modalHeaderColorDanger" class="modal-block modal-header-color modal-block-danger mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Are you sure?</h2>
                </header>
                <div class="card-body">
                    <div class="modal-wrapper">
                        <div class="modal-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="modal-text">
                            <h4>Danger</h4>
                            <p>Are you sure that you want to delete this Product?</p>
                        </div>
                    </div>
                </div>
                <footer class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-danger">Confirm</button>
                            <button type="button" class="btn btn-danger modal-dismiss"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        <!-- Modal Delete End -->
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
