{{-- Original Return Single Item --}} {{-- Obsolete - Returns / Transfer on Batch Out Modal on line item level 2023-12-05 --}}
{{-- <div class="row">                                    
    @if($dispatch->plant_id>0)  
        <div class="col-md-6">
            <label>Plant</label><br><h4>{{$dispatch->plant()->plant_number}}</h4>
        </div>
        <div class="col-md-6">
            <label>Reg No.</label><br><h4>{{$dispatch->plant()->reg_number}}</h4>                                                
        </div>
    @else
        <div class="col-md-6">
            <b>Vehicle</b>&nbsp;{{$dispatch->registration_number}}
        </div>
        <div class="col-md-6">
            <label></label><br><h4></h4><br>                                                
        </div>
    @endif 

    <div class="col-md-6">
        <label>Delivery Zone</label><br>
        <h4>{{$dispatch->delivery_zone}}</h4>
    </div>
    <div class="col-md-6">
        <x-form.number wire=0 name="weight_in" label="Weight In" value={{$weight_in}}/>
    </div> 
                                                                                
    <div class="col-md-6">
        <label>Product</label><br>
        
        @if ($dispatch->customer_id == '0')            
            <h4>{{($dispatch->product()!==null?$dispatch->product()->description:"")}}</h4>
        @else
            <h4>{{($dispatch->customer_product()!==null?$dispatch->customer_product()->description:"")}}</h4>    
        @endif
    </div>

    <hr>        
        
</div>     --}}