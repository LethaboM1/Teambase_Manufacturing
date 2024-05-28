<tr class="pointer">
    <td onclick="$('#complete_{{$transaction->id}}_btn').click()">{{$transaction->created_at}}</td>
    <td onclick="$('#complete_{{$transaction->id}}_btn').click()">{{($transaction->type=='REC'?$transaction->reference_number:"Return")}}</td>
    <td onclick="$('#complete_{{$transaction->id}}_btn').click()">{{$transaction->supplier()->name}}</td>
    <td onclick="$('#complete_{{$transaction->id}}_btn').click()">{{$transaction->registration_number}}</td>
    <td onclick="$('#complete_{{$transaction->id}}_btn').click()">{{$transaction->product()->code}} - {{$transaction->product()->description}}</td>
    <td onclick="$('#complete_{{$transaction->id}}_btn').click()">{{$archive ? $transaction->qty:$transaction->weight_in}}</td>
    <td onclick="$('#complete_{{$transaction->id}}_btn').click()">{{$transaction->status}}</td>
    <td>
        <button id="complete_{{$transaction->id}}_btn" href="#complete_receive_{{$transaction->id}}" class="btn btn-warning modal-sizes"><i class="fa fa-edit"></i></button>
        @if($transaction->type=='RET')
            <a target="_blank" href="{{url("dispatches/return-goods/".$transaction->id."/print")}}" class='btn btn-info '><i class="fa fa-print"></i></a>
        @endif
        <div id='complete_receive_{{$transaction->id}}' class='modal-block modal-block-lg mfp-hide'>
            <form action="{{url("dispatches/received-goods/{$transaction->id}")}}" method='post' enctype='multipart/form-data'>
                @csrf
                <section class='card'>
                    <header id='complete_receive_{{$transaction->id}}header' class='card-header'><h2 class='card-title'></h2></header>
                        <div class='card-body'>
                            <div class='modal-wrapper'>
                                <div class='modal-text'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Supplier</label>
                                            <h4>{{$transaction->supplier()->name}}</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Dispatch Number</label>
                                            <h4>{{$transaction->reference_number}}</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Registration No.</label>
                                            <h4>{{$transaction->registration_number}}</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Weighed In Date/Time</label>
                                            <h4>{{$transaction->weight_in_datetime}}</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Weighed In</label>
                                            <h4>{{$transaction->weight_in}}</h4>
                                        </div>
                                        @if($archive)
                                            <div class="col-md-6">
                                                <label class="form-label">Weighed Out Date/Time</label>
                                                <h4>{{$transaction->weight_out_datetime}}</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Weighed Out</label>
                                                <h4>{{$transaction->weight_out}}</h4>
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Qty</label>
                                                <h4>{{$transaction->qty}}</h4>
                                                
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Comment</label>
                                                <h4>{{$transaction->comment}}</h4>
                                                
                                            </div>                                            
                                        @else
                                            <div class="col-md-6">
                                                <x-form.number wire=0 name="weight_out" label="Weight Out" step="0.001" />
                                            </div>
                                            <div class="col-md-12">
                                                <x-form.textarea wire=0 name="comment" label="Comment" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer class='card-footer'>
                            <div class='row'>
                                <div class='col-md-12 text-right'>
                                    @if($transaction->status!='Completed')                                        
                                        <button type='submit' name='save' value='save' class='btn btn-primary'>Confirm</button>
                                    @else
                                        @if($transaction->type=='RET')
                                        <a target="_blank" href="{{url("dispatches/return-goods/".$transaction->id."/print")}}" class='btn btn-info '><i class="fa fa-print"></i>&nbsp;Print</a>
                                        @endif
                                    @endif
                                    <button class='btn btn-default modal-dismiss'>Cancel</button>
                                </div>
                            </div>
                        </footer>
                </section>
            </form>
        </div>
        
    </td>
</tr>