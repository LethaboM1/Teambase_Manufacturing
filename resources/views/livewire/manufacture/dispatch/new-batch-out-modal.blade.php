
<div class="row">
    @if($dispatch->plant_id>0)                                        
        <div class="col-md-6">
            <label>Plant</label><br><h4>{{$dispatch->plant()->plant_number}}</h4>
        </div>
        <div class="col-md-6">
            <label>Reg No.</label><br><h4>{{$dispatch->plant()->reg_number}}</h4>                                                
        </div>
        <div class="col-md-6">
            <label>Delivery Zone</label><br>
            <h4>{{$dispatch->delivery_zone}}</h4>
        </div>
    @else
        <div class="col-md-6">
            <b>Vehicle</b>&nbsp;{{$dispatch->registration_number}}
        </div>
    @endif        
    @if($dispatch->weight_in>0)
        <div class="col-md-6">
            <label>Weight In Date time</label><br>
            <h4>{{$dispatch->weight_in_datetime}}</h4>
        </div>
        <div class="col-md-6">
            <label>Weight In</label><br>
            <h4>{{$dispatch->weight_in}}</h4>
            <x-form.hidden wire=0 name="weight_in" value="{{$dispatch->weight_in}}"/>
        </div>
    @else
        <div class="col-md-6">
            <label>In Date time</label><br>
            <h4>{{$dispatch->weight_in_datetime}}</h4>
        </div>            
        <div class="col-md-6">            
        </div>
    @endif
    
    <hr>                                       

    @if ($dispatchaction == "new")
        
        <form class="form-horizontal form-bordered" method="get">
            <div class="form-group row pb-4" style="margin-bottom: 15px;">
                <div class="col-lg-6">
                    <div class="radio">
                        <label><input type="radio" name="customer_dispatch" value=0 checked="" wire:model="customer_dispatch">&nbsp&nbspJobcard Dispatch</label>&nbsp
                        <label><input type="radio" name="customer_dispatch" value=1 wire:model="customer_dispatch">&nbsp&nbspCustomer Dispatch</label>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-6">
            <x-form.input name="reference" label="Reference" />
        </div>
        <div class="col-md-6">            
            @if($customer_dispatch == 1)
                <x-form.select name="customer_id" label="Customer" :list="$customer_list" />
            @else
                <x-form.select name="job_id" label="Job card" :list="$jobcard_list" />
            @endif
            {{-- <x-form.select name="job_id" label="Job card" :list="$jobcard_list" /> --}}
        </div>
        <div class="col-md-6">           
            @if($customer_dispatch == 1)
                <x-form.select name="product_id" label="Product" :list="$products_list" />
            @else
                <x-form.select name="manufacture_jobcard_product_id" label="Product" :list="$manufacture_jobcard_products_list" />
            @endif            
        </div> {{-- Moved Into Line Items 2023-11-10 --}}
        <div class="col-md-6">
            <x-form.select name="delivery_zone" label="Delivery Zone" :list="$delivery_zone_list"/>
        </div>
        @if($dispatch->weight_in>0)
            <div class="col-md-4">
                <x-form.number name="weight_out" label="Weight Out" step="0.001" value={{$weight_out}}/>
            </div>
            <div class="col-md-4">
                <x-form.number name="dispatch_temp" label="Dispatched Temperature" step="0.01" value={{$dispatch_temp}}/>
            </div>
        {{-- @else             
            <div class="col-md-4">
                <x-form.number wire=0 name="qty" label="Qty Out" step="0.01" value={{$qty}}/>                            
            </div>
            <div class="col-md-4">            
            </div> --}} {{-- Moved into Line Items 2023-11-10 --}}
        @endif

        {{-- Line Items to be added to this Dispatch in addition to main item if weighed  --}}

        {{-- <div class='col-md-4'>           
            <br>
            @if ($extra_items_show == '0')
                <a wire:click="ExtraItemsShow" style="margin-top: 16px; width:100%" class="btn btn-primary btn-sm" title="Show additional Non-Weighed Items on this Dispatch.">
                    Show Additional Items&nbsp&nbsp                
                        <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <a wire:click="ExtraItemsShow" style="margin-top: 16px; width:100%" class="btn btn-primary btn-sm" title="Hide additional Non-Weighed Items on this Dispatch.">
                    Hide Additional Items&nbsp&nbsp
                        <i class="fas fa-chevron-left"></i>                                    
                </a>
            @endif            
        </div> --}} {{-- Obsolete 2023-11-10 --}}
        <div class='col-md-10'><br></div>
               
        <hr>
        {{-- Line Items 2023-11-10 --}}
        <form class="form-horizontal form-bordered" method="get">
            <table width="100%" class="table table-hover table-responsive-md mb-0">
                <thead>
                    <tr>
                        <th width="20%">Date</th>
                        <th width="40%">Description</th>
                        <th width="15%">Unit</th>
                        <th width="10%">Qty</th>
                        <th width="15%">Actions</th>
                        <th>
                            @if($add_extra_item_show == '0')
                                <a wire:click="AddExtraItemShow" class="btn btn-primary btn-sm" title="Add Item">
                                    <i class="fas fa-plus"></i>                    
                                </a>
                            @endif
                        </th>                                
                    </tr>
                </thead>                    
                    
                    {{-- Show Add Line for Extra Items --}}
                    @if($extra_item_error==true)
                        @if ($extra_item_message == 'Extra Product Item Added.')
                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Success!</strong>&nbsp;{{$extra_item_message}}
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-hidden='true' aria-label='Close'></button>
                            </div>    
                        @else
                            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                <strong>Error!</strong>&nbsp;{{$extra_item_message}}
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-hidden='true' aria-label='Close'></button>
                            </div>                                
                        @endif
                    @endif
                    @if($add_extra_item_show == '1')
                        <tr>   
                            <td><x-form.input name="extra_product_weight_in_date" value="{{$extra_product_weight_in_date}}" wire=0 disabled=1/></td>
                            @if($customer_dispatch == 1)
                                <td><x-form.select name="extra_product_id" :list="$products_list_unweighed" /></td>
                            @else
                                <td><x-form.select name="manufacture_jobcard_product_id" :list="$manufacture_jobcard_products_list_unweighed" /></td>                                
                            @endif                            
                            <td><x-form.input name="extra_product_unit_measure" value="{{$extra_product_unit_measure}}" wire=0 disabled=1/></td>
                            @if($dispatch->weight_in>0&&$only_one_weighed==0)
                                <td><x-form.number wire=0 value="{{$weight_out - $dispatch->weight_in}}" name="qty" step="0.001" disabled=1/></td>
                            @else
                                <td><x-form.number wire:model="extra_product_qty" value="{{$extra_product_qty}}" name="extra_product_qty" step="0.001" /></td>
                            @endif
                            <td>
                                <a wire:click="AddExtraItem('{{$dispatch->id}}')" class="btn btn-primary btn-sm" title="Add Item">
                                    <i class="fas fa-plus"></i>                    
                                </a>
                                <a wire:click="AddExtraItemShow" class="btn btn-primary btn-sm modal-basic" title="Cancel Add Item">
                                    <i class="fas fa-ban"></i>                    
                                </a>                                                                                                                   
                            </td>
                        </tr>
                    @endif

                    @if(count($extra_items)>0)
                        @foreach($extra_items as $extra_item)                                
                            <livewire:manufacture.dispatch.new-batch-out-extra-items-livewire key="{{ Str::random() }}" :extraitem="$extra_item" :dispatchaction="$dispatchaction" />
                        @endforeach
                        {{-- Refresh listeners on Modals --}}
                        <script>
                            setTimeout(function() {
                                $.getScript('{{url("js/examples/examples.modals.js")}}');
                                }, 500);
                        </script>
                    @else
                        <tr>
                            <td colspan="5">
                                &nbsp&nbspNo Products Added.
                            </td>                                
                        </tr>
                    @endif
            </table>
            
        </form>
        

        

        <div class="col-md-4">
            <x-form.hidden wire=0 name="customer_dispatch" value={{$customer_dispatch}}/>
        </div>                                                               
    @else
        <div class="col-md-6">                                            
            <label>Reference</label><br>
            <h4>{{$dispatch->reference}}</h4>
        </div>
        <div class="col-md-6">                                          
            @if($customer_dispatch == 1 || $dispatch->customer()!==null)
                <label>Customer</label><br>
                <h4>{{$dispatch->customer()->name}}</h4>                
            @else
                <label>Job No.</label><br>
                <h4>{{$dispatch->jobcard()!== null? $dispatch->jobcard()->jobcard_number:'None'}}</h4>
            @endif
            
        </div>
        {{-- <div class="col-md-6"><label>Product</label><br>
            @if($customer_dispatch == 1)                
                <h4>{{$dispatch->customer_product()->code}} {{$dispatch->customer_product()->description}}</h4>
            @else
                <h4>{{$dispatch->jobcard_product()->product()->code}} {{$dispatch->jobcard_product()->product()->description}}</h4>
            @endif            
        </div> --}} {{-- Moved into Line Items 2023-11-10 --}}
        <div class="col-md-6"><label>Delivery Zone</label><br>
            <h4>{{$dispatch->delivery_zone}}</h4>                                
        </div>
        @if($dispatch->weight_in>0)
            <div class="col-md-6">                                            
                <label>Weight Out Date time</label><br>
                <h4>{{$dispatch->weight_out_datetime}}</h4>
            </div>
            <div class="col-md-6">                                            
                <label>Weight Out</label><br>
                <h4>{{$dispatch->weight_out}}</h4>
            </div>
            <div class="col-md-6">
                <label>Dispatch Temperature</label><br>
                <h4>{{$dispatch->dispatch_temp}}</h4>
            </div>
        @else
            <div class="col-md-6">                                            
                <label>Out Date time</label><br>
                <h4>{{$dispatch->weight_out_datetime}}</h4>
            </div>
            <div class="col-md-6">                                                            
            </div>
            <div class="col-md-6">                
            </div>
        @endif 
        {{-- <div class="col-md-6">
            <label>Qty</label><br>
            <h4>{{$dispatch->qty}}</h4>
        </div> --}} {{-- Moved into Line Items 2023-11-10 --}}
        
        <div class='col-md-10'><br></div>
               
        <hr>
        {{-- Line Items 2023-11-10 --}}
        <form class="form-horizontal form-bordered" method="get">
            <table width="100%" class="table table-hover table-responsive-md mb-0">
                <thead>
                    <tr>
                        <th width="20%">Date</th>
                        <th width="40%">Description</th>
                        <th width="15%">Unit</th>
                        <th width="10%">Qty</th>
                        <th width="15%">Actions</th>                                                        
                    </tr>
                </thead>

                    @if(count($extra_items)>0)
                        @foreach($extra_items as $extra_item)                                
                            <livewire:manufacture.dispatch.new-batch-out-extra-items-livewire key="{{ Str::random() }}" :extraitem="$extra_item" :dispatchaction="$dispatchaction" />
                        @endforeach
                        {{-- Refresh listeners on Modals --}}
                        <script>
                            setTimeout(function() {
                                $.getScript('{{url("js/examples/examples.modals.js")}}');
                                }, 500);
                        </script>
                    @else
                        <tr>
                            <td colspan="5">
                                &nbsp&nbspNo Products Added.
                            </td>                                
                        </tr>
                    @endif
            </table>
            
        </form>        

        <div class="col-md-4">
            <x-form.hidden wire=0 name="customer_dispatch" value={{$customer_dispatch}}/>
        </div>

    @endif    
    
</div>   