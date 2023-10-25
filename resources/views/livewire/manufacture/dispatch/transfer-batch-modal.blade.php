
<div class="row">                                    
    
        <form class="form-horizontal form-bordered" method="get">
            <div class="form-group row pb-4" style="margin-bottom: 15px;">
                <div class="col-lg-6">
                    <div class="radio">
                        <label><input type="radio" name="customer_dispatch" value=0 checked="" wire:model="customer_dispatch">&nbsp&nbspJobcard Transfer</label>&nbsp
                        <label><input type="radio" name="customer_dispatch" value=1 wire:model="customer_dispatch">&nbsp&nbspCustomer Transfer</label>
                    </div>
                </div>
            </div>
        </form>

        <div class="col-md-6">
            {{-- @if ($dispatch->customer_id == '0') 
                <x-form.select name="job_id" label="New Job card" :list="$jobcard_list" />
            @else
                <x-form.select name="customer_id" label="New Customer" :list="$customer_list" />
            @endif --}}
            @if($customer_dispatch == 1)
                <x-form.select name="customer_id" label="Customer" :list="$customer_list" />
            @else
                <x-form.select name="job_id" label="Job card" :list="$jobcard_list" />
            @endif
        </div>
        
        <div class="col-md-6">
            @if($delivery || $customer_dispatch == 1)
                <x-form.select name="delivery_zone" label="New Delivery Zone" :list="$delivery_zone_list"/>  
            @endif            
        </div>

        <hr>
    
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
        <div class="col-md-6">
            <label>Weight In Date time</label><br><h4>{{$dispatch->weight_in_datetime}}</h4>
        </div>
        <div class="col-md-6">
            <label>Weight In</label><br><h4>{{$dispatch->weight_in}}</h4>                                            
        </div> 
        <div class="col-md-6">
            <label>Product</label><br>
            {{-- <h4>{{($dispatch->product()!==null?$dispatch->product()->description:"")}}</h4> --}}
            @if ($dispatch->customer_id == '0')            
                <h4>{{($dispatch->product()!==null?$dispatch->product()->description:"")}}</h4>
            @else
                <h4>{{($dispatch->customer_product()!==null?$dispatch->customer_product()->description:"")}}</h4>    
            @endif
        </div>

        <hr>                                        

        <div class="col-md-6">                                            
            <label>Weight Out Date time</label><br>
            <h4>{{$dispatch->weight_out_datetime}}</h4>
        </div>
        <div class="col-md-6">                                            
            <label>Weight Out</label><br>
            <h4>{{$dispatch->weight_out}}</h4>
        </div>
        <div class="col-md-6">
            <label>Qty</label><br>
            <h4>{{$dispatch->qty}}</h4>
        </div>

        {{-- @if ($dispatchaction == "transfering")                                           

            <div class="col-md-4">
                <x-form.datetime wire=0 name="weight_out_datetime" label="Date/Time" value={{$weight_out_datetime}} />
            </div>
            <div class="col-md-4">
                <x-form.number wire=0 name="weight_out" label="Weight Out" step="0.001" value={{$weight_out}}/>
            </div>
                                           
        @else
            <div class="col-md-6">                                            
                <label>Weight Out Date time</label><br>
                <h4>{{$dispatch->weight_out_datetime}}</h4>
            </div>
            <div class="col-md-6">                                            
                <label>Weight Out</label><br>
                <h4>{{$dispatch->weight_out}}</h4>
            </div>
            <div class="col-md-6">
                <label>Qty</label><br>
                <h4>{{$dispatch->qty}}</h4>
            </div>                                      
        @endif 
        Removed 2023-09-14 -> Returns are now one step process
        --}}
        
        
    </div>