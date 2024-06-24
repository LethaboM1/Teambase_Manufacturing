<form wire:submit.prevent='dispatch' enctype='multipart/form-data'>
    @csrf
    <section class='card'>
        <header id='addDispatchAdditionalHeader' class='card-header'>
            <h2 class='card-title'>Begin New Dispatch.</h2>
        </header>
        <div class='card-body'>
            <div class='modal-wrapper'>
                <div class='modal-text'>
                    <div>
                        <div class="row">
                            <div class="col-md-10" style="margin-top: 30px;">
                                <x-form.checkbox name="delivery" label="Product is to be Delivered" value=1 />
                            </div>

                            {{-- Determine if Job is for collection or delivery (Plant assigned or Reg no of Collector assigned) --}}
                            @if ($delivery)
                                <div class="col-md-10" style="margin-top: 10px;">
                                    <x-form.checkbox name="outsourced_transport" label="Delivery done by Outsourced Transport Contractor" value=1 />
                                </div>
                                @if($outsourced_transport != '1')
                                    <div class="col-md-6">
                                        <livewire:components.search-livewire name='plant_id' label="Plant" :list="$plant_list"/>                                        
                                    </div>                                    
                                @else
                                    <div class="col-md-6">
                                        <x-form.datalist label="Outsourced Contractor Name" name="outsourced_contractor" :list="$outsourced_contractors_list" />                                       
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input name="registration_number" label="Outsource Reg No." />
                                    </div>            
                                @endif
                            
                                {{-- <div class="col-md-6">
                                    <livewire:components.search-livewire name='plant_id' label="Plant"
                                        :list="$plant_list" />                                    
                                </div> --}}
                            @else
                                <div class="col-md-6">
                                    <x-form.input name="registration_number" label="Reg No." />
                                </div>
                            @endif
                            <div class="form-group row pb-4" style="margin-bottom: 15px;">
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label><input type="radio" name="customer_dispatch" value=0 checked=""
                                                wire:model="customer_dispatch">&nbsp&nbspJobcard Dispatch</label>&nbsp
                                        <label><input type="radio" name="customer_dispatch" value=1
                                                wire:model="customer_dispatch">&nbsp&nbspCustomer Dispatch</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <x-form.input name="reference" label="Reference" />
                            </div>
                            <div class="col-md-6">
                                @if ($customer_dispatch == 1)
                                    <livewire:components.search-livewire name='customer_id' label="Customer" />
                                    {{-- <x-form.select name="customer_id" label="Customer" :list="$customer_list" /> --}}
                                @else
                                    <livewire:components.search-livewire name='job_id' label="Job card" />
                                    {{-- <x-form.select name="job_id" label="Job card" :list="$jobcard_list" /> --}}
                                @endif
                                {{-- <x-form.select name="job_id" label="Job card" :list="$jobcard_list" /> --}}
                            </div>
                            @if ($delivery)
                                <div class="col-md-6">
                                    <x-form.select name="delivery_zone" label="Delivery Zone" :list="$delivery_zone_list" />
                                </div>
                            @endif

                            <div class='col-md-10'><br></div>
                            <hr>

                            <table width="100%" class="table table-hover table-responsive-md mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th width="20%">Date</th> --}}
                                        <th width="40%">Description</th>
                                        <th width="15%">Unit</th>
                                        <th width="10%">Qty</th>
                                        <th width="15%">Actions</th>
                                        <th>
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
                                @if (Session::get('alertError'))
                                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                        <strong>Error!</strong>&nbsp;{{ Session::get('alertError') }}
                                        <button type='button' class='btn-close' data-bs-dismiss='alert'
                                            aria-hidden='true' aria-label='Close'></button>
                                    </div>
                                @endif
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
                                    <tr>
                                        {{-- <td><x-form.input name="extra_product_weight_in_date" value="{{$extra_product_weight_in_date}}" wire=0 disabled=1/></td> --}}
                                        @if ($customer_dispatch == 1)
                                            <td>{{-- <x-form.select name="extra_product_id" :list="$products_list" /> --}}
                                                <livewire:components.search-livewire name='extra_product_id' 
                                                :value="$extra_product_id" :weighedlist="0"/>
                                            </td>
                                        @else
                                            <td>
                                                {{-- <x-form.select name="manufacture_jobcard_product_id"
                                                    :list="$manufacture_jobcard_products_list" /> --}}
                                                <livewire:components.search-livewire key="{{ now() }}"
                                                name='extra_manufacture_jobcard_product_id' :value="$manufacture_jobcard_product_id"
                                                :jobid="$job_id" :weighedlist="0"/>
                                            </td>
                                        @endif
                                        <td><x-form.input name="extra_product_unit_measure"
                                                value="{{ $extra_product_unit_measure }}" wire=0 disabled=1 /></td>
                                        <td><x-form.number wire:model="extra_product_qty"
                                                value="{{ $extra_product_qty }}" name="extra_product_qty"
                                                step="0.001" /></td>
                                        <td>
                                            <a wire:click="AddExtraItem" class="btn btn-primary btn-sm"
                                                title="Add Item">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a wire:click="$set('add_extra_item_show', false)" class="btn btn-primary btn-sm modal-basic"
                                                title="Cancel Add Item">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif

                                @if (count($extra_items) > 0)
                                    @foreach ($extra_items as $key => $extra_item)
                                        <tr>
                                            {{-- <td>{{$extraitem['the_date']}}</td> --}}
                                            <td>{{ $extra_item['description'] }}</td>
                                            <td>{{ $extra_item['unit_measure'] }}</td>
                                            <td>{{ \App\Http\Controllers\Functions::negate($extra_item['qty']) }}</td>
                                            <td>
                                                <a wire:click="removeExtraItem({{ $key }})"
                                                    class="btn btn-primary btn-sm" title="Remove Item">
                                                    <i class="fas fa-minus"></i>
                                                </a>
                                            </td>
                                        </tr>
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
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <footer class='card-footer'>
            <div class='row'>
                <div class='col-md-12 text-right'>
                    {{-- @if (count($extra_items) > 0) --}}
                        <button type='submit' class='btn btn-primary'>Confirm</button>
                    {{-- @endif --}}
                    <button wire:click="refreshNewDispatchModal" id="cancel_dispatch" class='btn btn-default modal-dismiss'>Cancel</button>
                </div>
            </div>
        </footer>
    </section>
    @push('scripts')
        <script>
            $(document).ready(function() {
                Livewire.on('closeDispatch', function($key) {
                    Livewire.emit('refreshNewDispatch');
                    $('#cancel_dispatch').click();
                    window.open('{{ url('dispatches/print') }}/' + $key +'?type=dispatch',
                        '_blank'); //$('#edit_btn_' + $key).click();

                })
            });
        </script>
    @endpush
</form>
