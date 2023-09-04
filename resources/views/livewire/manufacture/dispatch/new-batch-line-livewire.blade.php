<tr class="pointer">
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->created_at}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->dispatch_number}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->jobcard()->jobcard_number}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->jobcard()->contractor}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{($dispatch->plant()!==null?"{$dispatch->plant()->plant_number}-{$dispatch->plant()->make}-{$dispatch->plant()->reg_number}":$dispatch->registration_number)}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->qty}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->status}}</td>
    <td>
        <a id="edit_btn_{{$dispatch->id}}" href="#editDispatch_{{$dispatch->id}}" class="btn btn-primary btn-sm modal-basic"><i class="fas fa-edit"></i></a>                                                
        <div id='editDispatch_{{$dispatch->id}}' class='modal-block modal-block-lg mfp-hide'>           
            <section class='card'>
                <header id='editDispatch_{{$dispatch->id}}header' class='card-header'><h2 class='card-title'>Dispatch No. {{$dispatch->dispatch_number}}</h2></header>
                    {{-- @if(Session::get('dispatch_error'))
                        <small class="text-danger">{{Session::get('dispatch_error')}}</small>
                    @endif --}}

                    <div class='card-body'>
                        <div class='modal-wrapper'>
                            <div class='modal-text'>     
                                @if(Session::get('dispatch_error'))
                                    <small class="text-danger">{{Session::get('dispatch_error')}}</small>
                                @endif

                                <div class="row">                                    
                                @if($dispatch->plant_id>0)                                    
                                    <div class="col-md-6">
                                        <label>Plant</label><br><h4>{{$dispatch->plant()->plant_number}}</b>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Reg No.</label><br><h4>{{$dispatch->plant()->reg_number}}</b>
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
                                    </div>
                                    <hr>

                                   {{--  @if(Session::get('dispatch_error'))
                                        <small class="text-danger">{{Session::get('dispatch_error')}}</small>
                                    @endif --}}

                                    @if ($new == "true")
                                        <div class="col-md-4">
                                            <x-form.datetime name="weight_out_datetime" label="Date/Time"  />
                                        </div>
                                        <div class="col-md-4">
                                            <x-form.number name="weight_out" label="Weight Out" step="0.001" />
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
                                @if ($new == "true")
                                    <button wire:click="dispatch_out" type='submit'class='btn btn-primary'>Confirm</button>
                                    <button class='btn btn-default modal-dismiss'>Cancel</button></div> 
                                @else                                    
                                    <button class='btn btn-primary modal-dismiss'>Close</button></div>
                                @endif
                                
                            </div>
                        </div>
                    </footer>
            </section>
        </div>
    
    </td>
</tr>