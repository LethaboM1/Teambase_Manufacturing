
                    <div>
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
                            <label class="col-form-label" for="formGroupExampleInput">Qty</label>
                            <b><h3>{{$item->qty}}</h3></b>
                        </div>
                        <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                        <label class="col-form-label" for="formGroupExampleInput">Unit</label>
                        <b><h3>{{ucfirst($item->unit_measure)}}</h3></b>
                        </div>
                    </div>
                    <div class="row">
                        <x-form.select name="lab_test" label="Lab Test" :list="$lab_test_list" />
                    </div>
                    <hr>
                    <form wire:submit.prevent="submit">
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                <x-form.select name="product" label="Select product" :list="$product_list"  />
                                @error('product')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">                                
                                <x-form.number name="qty" label="Quantity" step="0.001"  />
                                @error('qty')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">                                
                                <x-form.select name="unit_measure" label="Unit" :value="$unit_measure" :list="$unit_measure_list" disabled="1" />
                            </div>
                            <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                <div>
                                    <br>
                                    <button class="btn btn-primary my-3 py-1 mx-3">Add Product</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
               
                    <div class="row">
                        <div class="card-body">
                            <h2 class="card-title">Raw Products to make ( 1 {{ucfirst($item->unit_measure)}})</h2>
                        </div>
                        <div class="card-body">
                            <div class="header-right">
                            </div>
                            <table width="100%" class="table table-responsive-md mb-0">
                                <thead>
                                    <tr>
                                        <th width="15%">Product Code</th>
                                        <th width="40%">Description</th>
                                        <th width="15%">Qty</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                @if(!empty($recipe_items))
                                    @foreach($recipe_items as $product)
                                        <livewire:manufacture.products.recipe.item :key="$product['id']" :item="$product" />
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No products loaded</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>