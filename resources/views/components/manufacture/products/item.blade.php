
<tr>
    <td>{{$item->created_at}}</td>
    <td>{{$item->code}}</td>
    <td>{{$item->description}}</td>
    <td>{{$item->qty}}</td>
    <td class="actions">
        <!-- Modal Edit Product -->
        <a class="mb-1 mt-1 mr-1 modal-basic" href="#modalviewProduct_{{$item->id}}" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Edit Product"><i class="fas fa-pencil-alt"></i></a>
 
        <!-- Modal Edit Product -->
        <div id="modalviewProduct_{{$item->id}}" class="modal-block modal-block-lg mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Edit Product</h2>
                </header>
                <form action="products/save" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-text">
                                <div class="row">
                                    <x-form.hidden name="id" :value="$item->id" />
                                    <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">                                    
                                        <x-form.input wire=0 name="code" label="Product Code" :value="$item->code" />
                                    </div>
                                    <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                        <x-form.input wire=0 name="description" label="Product Description" :value="$item->description" />
                                    </div>
                                    <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                        <label class="col-form-label" for="formGroupExampleInput">Qty</label>
                                        <input type="text" name="openvalue" class="form-control" value="{{$item->qty}}" disabled>
                                    </div>
                                    <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                        <x-form.select wire=0 name="unit_measure" label="Unit Measure" :value="$item->unit_measure" :list="$unit_measure_list" />
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
                </form>
            </section>
        </div>
        <!-- Modal Edit Product End -->

        <!-- Modal adjust stock -->
        <a class="mb-1 mt-1 mr-1 modal-basic" href="#modaladjust_{{$item->id}}" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Adjust Stock"><i class="fas fa-chart-simple"></i></a>
        <!-- Modal Adjust Product -->
        <div id="modaladjust_{{$item->id}}" class="modal-block modal-block-lg mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Adjust Product</h2>
                </header>
                <form action="products/adjust" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-text">
                                <div class="row">
                                
                                    <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                        <label class="col-form-label" for="formGroupExampleInput">Product Code</label>
                                        <b><h3>{{$item->code}}</h3></b>
                                        {{-- <input type="text" name="code" class="form-control"> --}}
                                    </div>
                                    <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                        <label class="col-form-label" for="formGroupExampleInput">Product Description</label>
                                        <b><h3>{{$item->description}}</h3></b>
                                    </div>
                                    <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                        <label class="col-form-label" for="formGroupExampleInput">Opening Value</label>
                                        <b><h3>{{$item->qty}}</h3></b>
                                    </div>
                                    <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Unit</label>
                                    <b><h3>{{$item->unit_measure}}</h3></b>
                                    </div>
                                    <hr>
                                    <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                        <label class="col-form-label" for="formGroupExampleInput">Current Value</label>
                                        <input type="text" name="current_value" class="form-control" value="{{$item->qty}}" disabled>
                                    </div>
                                    <x-form.hidden wire=0 name="id" :value="$item->id" />
                                    <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">                                    
                                        <x-form.input wire=0 name="new_value" label="New Value" />
                                    </div>
                                    <div class="col-sm-12 col-md-7 pb-sm-3 pb-md-0">
                                        <x-form.input wire=0 name="comment" label="Reason / Comment" />
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
                </form>
            </section>
        </div>
        <!-- Modal adjust Product End -->
        <!-- Modal adjust stock End -->
        <!-- Modal Edit Recipe -->
        <a class="mb-1 mt-1 mr-1 modal-basic" href="#modaladdrecipe" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Add Recipe"><i class="fas fa-list-alt"></i></a>
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
                                    <livewire:manufacture.products.search-select />
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
        <!-- Modal Edit recipe End -->
        <!-- Modal Delete -->
        <a class="mb-1 mt-1 mr-1 modal-basic" href="#modalHeaderColorDanger" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Delete Product"><i class="fas fa-trash-alt"></i></a>
        <!-- Modal Delete End -->
    </td>
</tr>