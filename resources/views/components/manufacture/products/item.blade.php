
<tr>
    <td>{{$item->created_at}}</td>
    <td>{{$item->code}}</td>
    <td>{{$item->description}}</td>
    <td>{{$item->qty}}</td>
    <td class="actions">
        @if(auth()->user()->role == 'manager')
            
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
                                    <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                        <x-form.checkbox wire=0 name="has_recipe" label="Has recipe?" value=1 :toggle="$item->has_recipe" />
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
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Qty</th>
                                    <th>User</th>
                                    <th>Comment</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @if($history->count())
                                @foreach($history as $line)
                                    <x-manufacture.products.adjust.line :line="$line" />
                                @endforeach
                            @else
                            @endif
                        </table>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-primary">Adjust Product</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>
        <!-- Modal adjust Product End -->
        @endif
        <!-- Modal adjust stock End -->
        
        @if($item->has_recipe)
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
                    <livewire:manufacture.products.recipe-add-livewire key="{{now()}}" :item="$item">
                    </div>
                </div>
                <footer class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-default modal-dismiss">Close</button>
                        </div>
                    </div>
                </footer>
                </div>
            </section>
        </div>
        <!-- Modal recipe End -->
        <!-- Modal Edit recipe End -->            
        @endif

        @if(auth()->user()->role == 'manager')
            <!-- Modal Delete -->
            <a class="mb-1 mt-1 mr-1 modal-basic" href="#modalHeader_{{$item->id}}" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Delete Product"><i class="fas fa-trash-alt"></i></a>
            <!-- Modal Delete -->
            <div id="modalHeader_{{$item->id}}" class="modal-block modal-header-color modal-block-danger mfp-hide">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Are you sure?</h2>
                    </header>
                    <form action="products/delete" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="modal-wrapper">
                                <div class="modal-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="modal-text">
                                    <h4>Danger</h4>
                                    <x-form.hidden wire=0 name="id" :value="$item->id" />
                                    <p>Are you sure that you want to delete this Product?</p>
                                    <x-form.hidden name="id" value="{{$item->id}}" />
                                </div>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-danger">Confirm</button>
                                    <button type="button" class="btn btn-danger modal-dismiss" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </footer>
                    </form>
                </section>
            </div>
            <!-- Modal Delete End -->
            <!-- Modal Delete End -->            
        @endif
    </td>
</tr>