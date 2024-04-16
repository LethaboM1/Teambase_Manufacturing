<form method='post' action="{{ url("dispatches/out/{$dispatch->id}") }}" enctype='multipart/form-data'>
    @csrf
    <section class='card'>
        <header id='editDispatch_{{ $dispatch->id }}header' class='card-header'>
            <h2 class='card-title'>Dispatch No. {{ $dispatch->dispatch_number }}</h2>
        </header>
        <div class='card-body'>            
            <div class='modal-wrapper'>
                <div class='modal-text'>
                    <div class="row">
                        @if ($dispatch->plant_id > 0)
                            <div class="col-md-6">
                                <label>Plant</label><br>
                                <h4>{{ $dispatch->plant()->plant_number }}</h4>                                
                            </div>
                            <div class="col-md-6">
                                <label>Reg No.</label><br>
                                <h4>{{ $dispatch->plant()->reg_number }}</h4>
                            </div>
                            <div class="col-md-6">
                                <label>Delivery Zone</label><br>
                                <h4>{{ $dispatch->delivery_zone }}</h4>
                            </div>
                        @else
                            @if ($dispatch->outsourced_contractor != '')
                                <div class="col-md-6">
                                    <label>Outsourced Contractor</label><br>
                                    <h4>{{ $dispatch->outsourced_contractor }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <label>Reg No.</label><br>
                                    <h4>{{ $dispatch->registration_number }}</h4>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label>Reg No.</label><br>
                                    <h4>{{ $dispatch->registration_number }}</h4>
                                </div>
                            @endif                            
                        @endif
                        @if ($dispatch->weight_in > 0)
                            <div class="col-md-6">
                                <label>Weight In Date time</label><br>
                                <h4>{{ $dispatch->weight_in_datetime }}</h4>
                            </div>
                            <div class="col-md-6">
                                <label>Weight In</label><br>
                                <h4>{{ $dispatch->weight_in }}</h4>
                                <x-form.hidden wire=0 name="weight_in" value="{{ $dispatch->weight_in }}" />
                            </div>
                        @else
                            <div class="col-md-6">
                                <label>In Date time</label><br>
                                <h4>{{ $dispatch->weight_in_datetime }}</h4>
                            </div>
                            <div class="col-md-6">
                            </div>
                        @endif

                        <hr>

                        @if ($dispatchaction == 'new')

                            <form class="form-horizontal form-bordered" method="get">
                                <div class="form-group row pb-4" style="margin-bottom: 15px;">
                                    <div class="col-lg-6">
                                        <div class="radio">
                                            <label><input type="radio" name="customer_dispatch" value=0 checked=""
                                                    wire:model="customer_dispatch">&nbsp&nbspJobcard
                                                Dispatch</label>&nbsp
                                            <label><input type="radio" name="customer_dispatch" value=1
                                                    wire:model="customer_dispatch">&nbsp&nbspCustomer Dispatch</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="col-md-6">
                                <x-form.datetime name="weight_out_datetime" label="Date/Time" />
                            </div>
                            <div class="col-md-6">
                                <x-form.input name="reference" label="Reference" />
                            </div>
                            <div class="col-md-6">
                                @if ($customer_dispatch == 1)
                                    <livewire:components.search-livewire name='customer_id' label="Customer"
                                        :value="$customer_id" />
                                    {{-- <x-form.select name="customer_id" label="Customer" :list="$customer_list" /> --}}
                                @else
                                    <livewire:components.search-livewire name='job_id' label="Job Card"
                                        :value="$job_id" />
                                    {{-- <x-form.select name="job_id" label="Job card" :list="$jobcard_list" /> --}}
                                @endif
                            </div>
                            {{-- <div class="col-md-6">
                                @if ($customer_dispatch == 1)
                                    <livewire:components.search-livewire name='product_id' label="Product"
                                        :value="$product_id" />                                    
                                @else
                                    <livewire:components.search-livewire key="{{ now() }}"
                                        name='manufacture_jobcard_product_id' label="Product" :value="$manufacture_jobcard_product_id"
                                        :jobid="$job_id" />                                    
                                @endif
                            </div> --}} {{-- Moved to Lines 2024-03-13 --}}
                            <div class="col-md-6">
                                <x-form.select name="delivery_zone" label="Delivery Zone" :list="$delivery_zone_list" />
                            </div>
                            @if ($dispatch->weight_in > 0)
                                <div class="col-md-4">                                    
                                    <x-form.number name="weight_out" label="Weight Out" step="0.001"/>
                                    @error('weight_out')
                                        <small class="text-danger"><strong>{{ $message }}</strong></small>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <x-form.number name="dispatch_temp" label="Dispatched Temperature" step="0.01"
                                        value={{ $dispatch_temp }} />
                                </div>
                                <div class="col-md-4">
                                    <b>Nett Weight</b><br><br>
                                    <h3>{{ $qty }}</h3>                                                                        
                                    {{-- <h3>{{ $qty_due }}</h3>  --}}
                                </div>                                
                                {{-- <div class="col-md-4">                      
                                </div>
                                <div class="col-md-4">                                    
                                </div>
                                <div class="col-md-4">
                                    <br><br>                                    
                                    <small class="text-danger"><strong>{{$over_under_variance}}</strong></small>                                    
                                </div>--}}  {{-- Moved to Lines 2024-03-13 --}}
                            @endif

                            <div class='col-md-12'><br></div>

                            <hr>
                            <form class="form-horizontal form-bordered" method="get">
                                <table width="100%" class="table table-hover table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                           {{--  <th width="20%">Date</th> --}}
                                            <th width="40%">Description</th>
                                            <th width="15%">Unit</th>
                                            <th width="15%">Qty</th>
                                            <th width="15%">Actions</th>
                                            <th width="15%">
                                                @if ($add_extra_item_show == false)
                                                    <a wire:click="$set('add_extra_item_show', true)" class="btn btn-primary btn-sm"
                                                        title="Add Item">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>

                                    {{-- Show Add Line for Extra Items --}}
                                    @if ($extra_item_error == true)
                                        @if ($extra_item_message == 'Extra Product Item Added.')
                                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                                <strong>Success!</strong>&nbsp;{{ $extra_item_message }}
                                                <button type='button' class='btn-close' data-bs-dismiss='alert'
                                                    aria-hidden='true' aria-label='Close'></button>
                                            </div>
                                        @else
                                            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                                <strong>Error!</strong>&nbsp;{{ $extra_item_message }}
                                                <button type='button' class='btn-close' data-bs-dismiss='alert'
                                                    aria-hidden='true' aria-label='Close'></button>
                                            </div>
                                        @endif
                                    @endif
                                    @if ($add_extra_item_show == true)
                                        <td>
                                           {{--  <td><x-form.input name="extra_product_weight_in_date"
                                                    value="{{ $extra_product_weight_in_date }}" wire=0 disabled=1 />
                                            </td> --}}
                                            @if ($customer_dispatch == 1)
                                                {{-- <td><x-form.select name="extra_product_id" :list="$products_list_unweighed" /></td> --}}
                                                <livewire:components.search-livewire name='extra_product_id' 
                                                :value="$extra_product_id" :weighedlist="$weighedlist"/>
                                            @else
                                                {{-- <td><x-form.select name="extra_manufacture_jobcard_product_id"
                                                        :list="$manufacture_jobcard_products_list_unweighed" /></td> --}}
                                                <livewire:components.search-livewire key="{{ now() }}"
                                                name='extra_manufacture_jobcard_product_id' :value="$manufacture_jobcard_product_id"
                                                :jobid="$job_id" :weighedlist="$weighedlist"/>
                                            @endif
                                            <td><x-form.input name="extra_product_unit_measure"
                                                    value="{{ $extra_product_unit_measure }}" wire=0 disabled=1 /></td>
                                            
                                                {{-- @dd($extra_product_weighed) --}}
                                                @if($extra_product_weighed > 0)
                                                <td>
                                                   <h3 class="mt-2"> {{ $qty }}</h3>
                                                @else
                                                <td>
                                                    <x-form.number wire:model="extra_product_qty"
                                                    value="{{ $extra_product_qty }}" name="extra_product_qty"
                                                    step="0.001" />                                                
                                                @endif                                                                                       
                                            </td>
                                            <td>
                                                <a wire:click="AddExtraItem('{{ $dispatch->id }}')"
                                                    class="btn btn-primary btn-sm" title="Add Item">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <a wire:click="$set('add_extra_item_show', false)"
                                                    class="btn btn-primary btn-sm modal-basic"
                                                    title="Cancel Add Item">
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif

                                    @if (count($extra_items) > 0)
                                        @foreach ($extra_items as $extra_item)                                                                                    
                                            <livewire:manufacture.dispatch.new-batch-out-extra-items-livewire
                                                key="{{ Str::random() }}" :extraitem="$extra_item" :dispatchaction="$dispatchaction"
                                                overundervariance="{{array_key_exists($extra_item['manufacture_jobcard_product_id'], $over_under_variance) == true ? $over_under_variance[$extra_item['manufacture_jobcard_product_id']] : ''}}"
                                                />                                                 
                                        @endforeach
                                        {{-- Refresh listeners on Modals --}}
                                        <script>
                                            setTimeout(function() {
                                                $.getScript('{{ url('js/examples/examples.modals.js') }}');
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
                                <x-form.hidden wire=0 name="customer_dispatch" value="{{ $customer_dispatch }}" />                                
                                <x-form.hidden wire=0 name="qty" value="{{ $qty }}" />
                                <x-form.hidden wire=0 name="qty_due" value="{{ $qty_due }}" />                                
                                <x-form.hidden wire=0 name="over_under_variance" value="{{ $over_under_variance_encoded }}" />
                            </div>
                        @else
                            <div class="col-md-6">
                                <label>Reference</label><br>
                                <h4>{{ $dispatch->reference }}</h4>
                            </div>
                            <div class="col-md-6">
                                @if ($customer_dispatch == 1 || $dispatch->customer() !== null)
                                    <label>Customer</label><br>
                                    <h4>{{ $dispatch->customer()->name }}</h4>
                                @else
                                    <label>Job No.</label><br>
                                    <h4>{{ $dispatch->jobcard() !== null ? $dispatch->jobcard()->jobcard_number : 'None' }}
                                    </h4>
                                @endif

                            </div>

                            
                            @if ($customer_dispatch != 1 || $dispatch->customer() == null)
                                <div class="col-md-6">
                                    <label>Site No.</label><br>
                                    <h4>{{ $dispatch->jobcard() !== null ? $dispatch->jobcard()->site_number : '-' }}
                                    </h4>
                                </div>
                            @endif

                            

                            <div class="col-md-6"><label>Delivery Zone</label><br>
                                <h4>{{ $dispatch->delivery_zone }}</h4>
                            </div>
                            @if ($dispatch->weight_in > 0)
                                <div class="col-md-6">
                                    <label>Weight Out Date time</label><br>
                                    <h4>{{ $dispatch->weight_out_datetime }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <label>Weight Out</label><br>
                                    <h4>{{ $dispatch->weight_out }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <label>Dispatch Temperature</label><br>
                                    <h4>{{ $dispatch->dispatch_temp }}</h4>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label>Out Date time</label><br>
                                    <h4>{{ $dispatch->weight_out_datetime }}</h4>
                                </div>
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                </div>
                            @endif
                            <hr>
                            {{-- <div class="col-md-6"><label>Product</label><br>
                                @if ($customer_dispatch == 1)
                                    @if ($dispatch->customer_weighed_product())
                                        <h4>{{ $dispatch->customer_weighed_product()->code }}
                                            {{ $dispatch->customer_weighed_product()->description }}</h4>
                                    @endif
                                @else
                                    @if ($dispatch->jobcard_product())
                                        <h4>{{ $dispatch->jobcard_product()->product()->code }}
                                            {{ $dispatch->jobcard_product()->product()->description }}</h4>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-2">
                                <label>Qty</label><br>
                                <h4>{{ $dispatch->qty }}</h4>
                            </div> --}} {{-- Moved to Lines 2024-03-15 --}}
                            {{-- @if ($dispatchaction == 'view' && $dispatch->qty > '0')
                                <div class="col-md-2">
                                    <label></label><br>
                                </div>
                                <div class="col-md-2">
                                    <label></label><br>
                                    <a wire:click="startReturnItem('{{ $dispatch->id }}')"
                                        class="btn btn-primary btn-sm" title="Return Item">
                                        <i class="fas fa-rotate-left"></i>
                                    </a>
                                    @if ($dispatch->job_id != '0')
                                        <a wire:click="startTransferItem('{{ $dispatch->id }}')"
                                            class="btn btn-primary btn-sm" title="Transfer Item">
                                            <i class="fas fa-right-left"></i>
                                        </a>
                                    @endif
                                </div>
                            @elseif($dispatchaction == 'returning')
                                @if ($dispatch->weight_out > 0)
                                    <div class="col-md-2">
                                        <label>Return Weight In</label><br>
                                        <x-form.number name="dispatch_return_weight_in" step="0.001"
                                            value="{{ $dispatch_return_weight_in }}" />
                                        @error('dispatch_return_weight_in')
                                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                                        @enderror
                                        @error('dispatch.qty')
                                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                                        @enderror
                                    </div>
                                @else
                                    <div class="col-md-2">
                                        <label>Adj Qty</label><br>
                                        <x-form.number name="dispatch_adjust_qty" step="0.001"
                                            value="{{ $dispatch_adjust_qty }}" />
                                    </div>
                                @endif
                                <div class="col-md-2">
                                    <label></label><br>
                                    <a wire:click="confirmReturnItem('{{ $dispatch->id }}')"
                                        class="btn btn-success btn-sm" title="Confirm Return">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a wire:click="cancelReturnItem('{{ $dispatch->id }}')"
                                        class="btn btn-danger btn-sm" title="Cancel Return">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                </div>
                            @elseif($dispatchaction == 'transfering')
                                <div class="col-md-2">
                                    <livewire:components.search-livewire name='transfer_job_id' label="To Job Card"
                                        :value="$transfer_job_id" />
                                    @error('transfer_job_id')
                                        <small class="text-danger"><strong>{{ $message }}</strong></small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label></label><br>
                                    <a wire:click="confirmTransferItem('{{ $dispatch->id }}')"
                                        class="btn btn-success btn-sm" title="Confirm Transfer">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a wire:click="cancelTransferItem('{{ $dispatch->id }}')"
                                        class="btn btn-danger btn-sm" title="Cancel Transfer">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                </div>
                            @endif                             
                            <div class='col-md-10'><br></div>
                            @if ($return_item_success == true)
                                <div class='alert alert-success alert-dismissible fade show' role='alert'
                                    id='alertReturn'>
                                    <strong>Success!</strong>&nbsp;{{ $return_item_message }}
                                    <button type='button' class='btn-close' data-bs-dismiss='alert'
                                        aria-hidden='true' aria-label='Close'></button>
                                </div>
                            @endif

                            <div class='col-md-10'><br></div>

                            <hr> --}} {{-- Moved to Lines 2024-03-15 --}}
                            {{-- Line Items 2023-11-10 --}}
                            <form class="form-horizontal form-bordered" method="get">
                                <table width="100%" class="table table-hover table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            {{-- <th width="20%">Date</th> --}}
                                            <th width="40%">Description</th>
                                            <th width="15%">Unit</th>
                                            <th width="12%">Qty</th>
                                            <th width="22%"></th>
                                            <th width="11%">Actions</th>
                                        </tr>
                                    </thead>

                                    @if (count($extra_items) > 0)                                    
                                        @if ($return_extraitem_success == true)
                                            <div class='alert alert-success alert-dismissible fade show'
                                                role='alert' id='alertReturn'>
                                                <strong>Success!</strong>&nbsp;{{ $return_extraitem_message }}
                                                <button type='button' class='btn-close' data-bs-dismiss='alert'
                                                    aria-hidden='true' aria-label='Close'></button>
                                            </div>
                                        @elseif ($transfer_extraitem_success == true)
                                            <div class='alert alert-success alert-dismissible fade show'
                                                role='alert' id='alertTransfer'>
                                                <strong>Success!</strong>&nbsp;{{ $transfer_extraitem_message }}
                                                <button type='button' class='btn-close' data-bs-dismiss='alert'
                                                    aria-hidden='true' aria-label='Close'></button>
                                            </div>
                                        @endif
                                        @foreach ($extra_items as $extra_item)
                                            <livewire:manufacture.dispatch.new-batch-out-extra-items-livewire
                                                key="{{ Str::random() }}" :extraitem="$extra_item" :dispatchaction="$dispatchaction"
                                                overundervariance="{{array_key_exists($extra_item['manufacture_jobcard_product_id'], $over_under_variance) == true ? $over_under_variance[$extra_item['manufacture_jobcard_product_id']] : ''}}"
                                                />                                            
                                        @endforeach
                                        {{-- Refresh listeners on Modals --}}
                                        <script>
                                            setTimeout(function() {
                                                $.getScript('{{ url('js/examples/examples.modals.js') }}');
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
                                <x-form.hidden wire=0 name="customer_dispatch" value={{ $customer_dispatch }} />
                            </div>

                        @endif

                    </div>
                </div>
            </div>
        </div>
        <footer class='card-footer'>
            <div class='row'>
                <div class='col-md-12 text-right'>
                    @if ($dispatchaction == 'new')
                        <button type='submit'class='btn btn-primary'>Confirm</button>
                        <a id="edit_btn_{{ $dispatch->id }}" href="#delete_{{ $dispatch->id }}"
                            class="btn btn-danger modal-basic" title="View Archived Dispatch">Delete</a>
                        <button class='btn btn-default modal-dismiss'>Cancel</button>

                        <div id='delete_{{ $dispatch->id }}' class='modal-block modal-block-lg mfp-hide'>
                            <form method='post' enctype='multipart/form-data'>
                                @csrf
                                <section class='card'>
                                    <header id='delete_{{ $dispatch->id }}header' class='card-header'>
                                        <h2 class='card-title'></h2>
                                    </header>
                                    <div class='card-body'>
                                        <div class='modal-wrapper'>
                                            <div class='modal-text'>
                                                <label>Are you sure you want to delete this dispatch?</label><br>
                                            </div>
                                        </div>
                                    </div>
                                    <footer class='card-footer'>
                                        <div class='row'>
                                            <div class='col-md-12 text-right'>
                                                <a class='btn btn-success btn-sm'
                                                    href="{{ url("dispatches/delete/{$dispatch->id}") }}">Yes</a>
                                                <a class='btn btn-danger btn-sm modal-dismiss'>No</a>
                                            </div>
                                        </div>
                                    </footer>
                                </section>
                            </form>
                        </div>
                    @else
                        <a target="_blank" href="{{ url("dispatches/print/{$dispatch->id}?type=dispatch") }}"
                            class="btn btn-default"><i class="fa fa-print"></i>&nbsp;Print</a>
                        <button class='btn btn-primary modal-dismiss'
                            id='closeModalBtn_{{ $dispatch->id }}'>Close</button>
                    @endif

                </div>
            </div>
        </footer>
    </section>    
</form>
{{-- @if(Session::get('print_return'))                        
    <script>
        console.log(window.open('{{url("dispatches/print_return/".Session::get('print_return_dispatch_id')."?type=return&extraitemid=".Session::get('print_return'))}}','_blank'));
    </script>
@endif --}}
{{-- @dd(Session::get('print_return')); --}}

{{-- @if(Session::get('print_return'))                        
    <script>
        var $printurl = '{{url("dispatches/print_return/".Session::get('print_return_dispatch_id')."?extraitemid=".Session::get('print_return')."&type=return")}}';
        window.open($printurl.replace('&amp;', '&'),'_blank');
    </script>
@endif --}}