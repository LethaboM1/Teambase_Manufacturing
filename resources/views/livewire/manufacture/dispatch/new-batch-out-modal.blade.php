
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
    <div class="col-md-6">
        <label>Weight In Date time</label><br>
        <h4>{{$dispatch->weight_in_datetime}}</h4>
    </div>
    <div class="col-md-6">
        <label>Weight In</label><br>
        <h4>{{$dispatch->weight_in}}</h4>
        <x-form.hidden wire=0 name="weight_in" value="{{$dispatch->weight_in}}"/>
    </div>
    <hr>                                       

    @if ($dispatchaction == "new")
        <div class="col-md-6">
            <x-form.input name="reference" label="Reference" />
        </div>
        <div class="col-md-6">
            <x-form.select name="job_id" label="Job card" :list="$jobcard_list" />
        </div>
        <div class="col-md-6">           
            <x-form.select name="manufacture_jobcard_product_id" label="Product" :list="$manufacture_jobcard_products_list" />
        </div>
        <div class="col-md-6">
            <x-form.select name="delivery_zone" label="Delivery Zone" :list="$delivery_zone_list"/>
        </div>
        <div class="col-md-4">
            <x-form.number wire=0 name="weight_out" label="Weight Out" step="0.001" value={{$weight_out}}/>
        </div>
        <div class="col-md-4">
            <x-form.number wire=0 name="dispatch_temp" label="Dispatched Temperature" step="0.01" value={{$dispatch_temp}}/>
        </div>                                                               
    @else
        <div class="col-md-6">                                            
            <label>Reference</label><br>
            <h4>{{$dispatch->reference}}</h4>
        </div>
        <div class="col-md-6">                                          
            <label>Job No.</label><br>
            <h4>{{$dispatch->jobcard()->jobcard_number}}</h4>
        </div>
        <div class="col-md-6"><label>Product</label><br>
            <h4>{{$dispatch->jobcard_product()->product()->code}} {{$dispatch->jobcard_product()->product()->description}}</h4>                                
        </div>
        <div class="col-md-6"><label>Delivery Zone</label><br>
            <h4>{{$dispatch->delivery_zone}}</h4>                                
        </div>
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
        <div class="col-md-6">
            <label>Qty</label><br>
            <h4>{{$dispatch->qty}}</h4>
        </div>                                        
    @endif    
    
</div>   