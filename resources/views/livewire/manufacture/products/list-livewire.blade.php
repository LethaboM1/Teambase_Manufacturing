 
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
                                    <x-form.number wire=0 step="0.001" name="opening_balance" label="Opening Balance" :value="old('opening_balance')" />
                                </div>
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                    <x-form.select wire=0 name="unit_measure" label="Unit Measure" :value="old('unit_measure')" :list="$unit_measure_list" />
                                </div>
                                <br>
                                <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                    {{-- <div class="checkbox-custom checkbox-default">
                                        <input id="checkbox1" name="has_recipe" type="checkbox" value="1">
                                        <label for="checkbox1">Requires recipe</label>
                                    </div> --}}
                                    <x-form.checkbox wire=0 name="has_recipe" label="Has a recipe?" :toggle="old('has_recipe')" :value="1" />
                                </div>
                                <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">                                    
                                    <x-form.checkbox wire=0 name="weighed_product" label="Product Weighed when Sold" :toggle="old('weighed_product')" :value="1" />
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
                    <button  type="button" class="btn btn-primary" wire:click="fix_items"><i class="fa fa-gear"></i>&nbsp;Fix Weighed Items</button><br><br>
                    @if (Session::get('alertMessage'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    
                        <strong>Success</strong> {{Session::get('alertMessage')}}
                    </div>                        
                    @endif
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="DispatchTabs" role="tablist">
                        <li class="nav-item {{($tab=='all'?'active':'')}}" role="presentation">
                            <button class="nav-link {{($tab=='all'?'active':'')}}" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="{{($tab=='all'?'true':'false')}}">All Products</button>
                        </li>
                        <li class="nav-item {{($tab=='recipe'?'active':'')}}" role="presentation">
                            <button class="nav-link {{($tab=='recipe'?'active':'')}}" id="recipe-tab" data-bs-toggle="tab" data-bs-target="#recipe" type="button" role="tab" aria-controls="recipe" aria-selected="{{($tab=='recipe'?'true':'false')}}">Products with Recipes</button>
                        </li>
                        <li class="nav-item {{($tab=='raw'?'active':'')}}" role="presentation">
                            <button class="nav-link {{($tab=='raw'?'active':'')}}" id="raw-tab" data-bs-toggle="tab" data-bs-target="#raw" type="button" role="tab" aria-controls="raw" aria-selected="{{($tab=='raw'?'true':'false')}}">Raw Material Products</button>
                        </li>
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane {{($tab=='all'?'active':'')}}" id="all" role="tabpanel" aria-labelledby="all-tab">

                            <div class="header-right">
                                {{-- <form action="#" class="search nav-form"> --}}
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" wire:model.debounce="search" placeholder="Search Product...">
                                        <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                    </div>
                                {{-- </form> --}}
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
                                    @if(count($products_list)>0)
                                        @foreach($products_list as $product)
                                            <x-manufacture.products.item :item="$product" />
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">No products..</td>
                                        </tr>
                                    @endif
                            </table>    
                            {{ $products_list->links() }}    
                        </div>
                        <div class="tab-pane {{($tab=='recipe'?'active':'')}}" id="recipe" role="tabpanel" aria-labelledby="recipe-tab">

                            <div class="header-right">
                                {{-- <form action="#" class="search nav-form"> --}}
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search_recipe" wire:model.debounce="search_recipe" placeholder="Search Product...">
                                        <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                    </div>
                                {{-- </form> --}}
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
                                    @if(count($products_recipe_list)>0)
                                        @foreach($products_recipe_list as $product)
                                            <x-manufacture.products.item :item="$product" />
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">No products..</td>
                                        </tr>
                                    @endif
                            </table>    
                            {{ $products_recipe_list->links() }}    
                        </div>
                        <div class="tab-pane {{($tab=='raw'?'active':'')}}" id="raw" role="tabpanel" aria-labelledby="raw-tab">

                            <div class="header-right">
                                {{-- <form action="#" class="search nav-form"> --}}
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search_raw" wire:model.debounce="search_raw" placeholder="Search Product...">
                                        <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                    </div>
                                {{-- </form> --}}
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
                                    @if(count($products_raw_list)>0)
                                        @foreach($products_raw_list as $product)
                                            <x-manufacture.products.item :item="$product" />
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">No products..</td>
                                        </tr>
                                    @endif
                            </table>    
                            {{ $products_raw_list->links() }} 
                        </div>
                    </div>
         
                </div> 
            </section>
        </div>
    </div>
