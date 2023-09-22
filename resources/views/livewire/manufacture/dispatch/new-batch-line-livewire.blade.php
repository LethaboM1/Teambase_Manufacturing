<tr class="pointer">
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->created_at}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->dispatch_number}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->jobcard()->jobcard_number}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->jobcard()->contractor}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{($dispatch->plant()!==null?"{$dispatch->plant()->plant_number}-{$dispatch->plant()->make}-{$dispatch->plant()->reg_number}":$dispatch->registration_number)}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->product()->description}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->qty}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->status}}</td>
    <td style="width:100px">
        @if ($dispatchaction == "new")
            <a id="edit_btn_{{$dispatch->id}}" href="#editDispatch_{{$dispatch->id}}" class="btn btn-primary btn-sm modal-basic" title="Process Loading Dispatch">
                <i class="fas fa-edit"></i>
            </a>
        @else
            <a id="edit_btn_{{$dispatch->id}}" href="#editDispatch_{{$dispatch->id}}" class="btn btn-primary btn-sm modal-basic" title="View Archived Dispatch">
                <i class="fas fa-eye"></i>                    
            </a>
            @if ($dispatch->qty !== '0.000')
                <a id="return_btn_{{$dispatch->id}}" href="#returnDispatch_{{$dispatch->id}}" class="btn btn-primary btn-sm modal-basic" title="Return Product on this Dispatch">
                    <i class="fas fa-rotate-left"></i>                    
                </a>
                <a id="transfer_btn_{{$dispatch->id}}" href="#transferDispatch_{{$dispatch->id}}" class="btn btn-primary btn-sm modal-basic" title="Transfer Product on this Dispatch to another Jobcard">
                    <i class="fas fa-right-left"></i>                    
                </a>
            @endif
        @endif                                          
        
          <div id='editDispatch_{{$dispatch->id}}' class='modal-block modal-block-lg mfp-hide'>           
            <form method='post' action="{{url("dispatches/out/{$dispatch->id}")}}" enctype='multipart/form-data'>
                @csrf
                <section class='card'>
                    <header id='editDispatch_{{$dispatch->id}}header' class='card-header'><h2 class='card-title'>Dispatch No. {{$dispatch->dispatch_number}}</h2></header>
                        <div class='card-body'>                        
                            
                            <div class='modal-wrapper'>
                                <div class='modal-text'> 
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
                                        <div class="col-md-6">
                                            <label>Product</label><br>
                                            <h4>{{$dispatch->product()->description}}</h4>
                                        </div>

                                        <hr>                                        

                                        @if ($dispatchaction == "new")
                                            

                                            {{-- <div class="col-md-4">
                                                <x-form.datetime wire=0 name="weight_out_datetime" label="Date/Time" value={{$weight_out_datetime}} />
                                            </div> 
                                            //removed 2023-09-13 Marcia
                                            --}}
                                            <div class="col-md-4">
                                                <x-form.number wire=0 name="weight_out" label="Weight Out" step="0.001" value={{$weight_out}}/>
                                            </div>
                                            <div class="col-md-4">
                                                <x-form.number wire=0 name="dispatch_temp" label="Dispatched Temperature" step="0.01" value={{$dispatch_temp}}/>
                                            </div>
                                            <div class="col-md-4">
                                                {{-- <label class="form-label col-form-label">Qty</label>
                                                <h4>{{$qty}}</h4> --}}
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
                                                <label>Dispatch Temperature</label><br>
                                                <h4>{{$dispatch->dispatch_temp}}</h4>
                                            </div> 
                                            <div class="col-md-6">
                                                <label>Qty</label><br>
                                                <h4>{{$dispatch->qty}}</h4>
                                            </div>                                        
                                        @endif
                                        
                                        
                                    </div>     
                                </div>
                            </div>
                        </div>
                        <footer class='card-footer'>
                            <div class='row'>
                                <div class='col-md-12 text-right'>
                                    @if ($dispatchaction == "new")
                                        <button type='submit'class='btn btn-primary'>Confirm</button>
                                        <button class='btn btn-default modal-dismiss'>Cancel</button>                           
                                    @else                                    
                                        <button class='btn btn-primary modal-dismiss'>Close</button>
                                    
                                    @endif
                                    
                                </div>
                            </div>
                        </footer>
                </section>
            </form>
        </div>
        
        <div id='returnDispatch_{{$dispatch->id}}' class='modal-block modal-block-lg mfp-hide'>           
            <form method='post' action="{{url("dispatches/return/{$dispatch->id}")}}" enctype='multipart/form-data'>
                @csrf
                <section class='card'>
                    <header id='returnDispatch_{{$dispatch->id}}header' class='card-header'><h2 class='card-title'>Return on Dispatch No. {{$dispatch->dispatch_number}}</h2></header>
                        <div class='card-body'>                        
                            
                            <div class='modal-wrapper'>
                                <div class='modal-text'> 
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
                                        {{-- <div class="col-md-6">
                                            <x-form.datetime wire=0 name="weight_in_datetime" label="Date/Time" value={{$weight_in_datetime}}/>
                                        </div> 
                                        Dates added on post now 2023-09-14
                                        --}}
                                        <div class="col-md-6">
                                            <x-form.number wire=0 name="weight_in" label="Weight In" value={{$weight_in}}/>
                                        </div>                                                                           
                                        <div class="col-md-6">
                                            <label>Product</label><br>
                                            <h4>{{$dispatch->product()->description}}</h4>
                                        </div>

                                        <hr>
                                        
                                        {{-- <div class="col-md-6">                                            
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
                                        Not Required on Returns
                                        --}}

                                        {{-- @if ($dispatchaction == "returning")                                           

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
                                </div>
                            </div>
                        </div>
                        <footer class='card-footer'>
                            <div class='row'>
                                <div class='col-md-12 text-right'>                                    
                                        <button type='submit'class='btn btn-primary'>Confirm</button>
                                        <button class='btn btn-default modal-dismiss'>Cancel</button>                                                              
                                    
                                </div>
                            </div>
                        </footer>
                </section>
            </form>
        </div>

        <div id='transferDispatch_{{$dispatch->id}}' class='modal-block modal-block-lg mfp-hide'>           
            <form method='post' action="{{url("dispatches/transfer/{$dispatch->id}")}}" enctype='multipart/form-data'>
                @csrf
                <section class='card'>
                    <header id='transferDispatch_{{$dispatch->id}}header' class='card-header'><h2 class='card-title'>Transfer on Dispatch No. {{$dispatch->dispatch_number}} to Another Jobcard</h2></header>
                        <div class='card-body'>                        
                            
                            <div class='modal-wrapper'>
                                <div class='modal-text'> 
                                    <div class="row">                                    
                                    @if($dispatch->plant_id>0)
                                        <div class="col-md-6">
                                            <x-form.select name="job_id" label="New Job card" :list="$jobcard_list" />
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <x-form.select name="delivery_zone" label="New Delivery Zone" :list="$delivery_zone_list"/>
                                        </div>

                                        <hr>
                                        
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
                                            <h4>{{$dispatch->product()->description}}</h4>
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
                                </div>
                            </div>
                        </div>
                        <footer class='card-footer'>
                            <div class='row'>
                                <div class='col-md-12 text-right'>                                    
                                        <button type='submit'class='btn btn-primary'>Confirm</button>
                                        <button class='btn btn-default modal-dismiss'>Cancel</button>                                                              
                                    
                                </div>
                            </div>
                        </footer>
                </section>
            </form>
        </div>
        
    
    </td>
</tr>