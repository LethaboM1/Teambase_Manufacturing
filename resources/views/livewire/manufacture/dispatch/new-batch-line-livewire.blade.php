<tr class="pointer">
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->created_at}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->dispatch_number}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{($dispatch->jobcard()!==null?$dispatch->jobcard()->jobcard_number:"")}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{($dispatch->jobcard()!==null?$dispatch->jobcard()->contractor:"")}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{($dispatch->plant()!==null?"{$dispatch->plant()->plant_number}-{$dispatch->plant()->make}-{$dispatch->plant()->reg_number}":$dispatch->registration_number)}}</td>
    <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{($dispatch->product()!==null?$dispatch->product()->description:"")}}</td>
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
                                    <livewire:manufacture.dispatch.new-batch-out-modal :dispatch="$dispatch" :dispatchaction="$dispatchaction">  
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
                                        <a target="_blank" href="{{url("dispatches/print/{$dispatch->id}")}}" class="btn btn-default"><i class="fa fa-print"></i>&nbsp;Print</a>
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
                                    <livewire:manufacture.dispatch.return-batch-modal :dispatch="$dispatch" />
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
                    <header id='transferDispatch_{{$dispatch->id}}header' class='card-header'><h2 class='card-title'>Transfer on Dispatch No. {{$dispatch->dispatch_number}} </h2></header>
                        <div class='card-body'>                        
                            
                            <div class='modal-wrapper'>
                                <div class='modal-text'> 
                                    <livewire:manufacture.dispatch.transfer-batch-modal :dispatch="$dispatch" />     
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