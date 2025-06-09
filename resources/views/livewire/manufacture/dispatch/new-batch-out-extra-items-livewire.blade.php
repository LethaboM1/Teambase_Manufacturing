<tr>   
    
    {{-- <td>{{$extraitem['the_date']}}</td> --}}
    <td>
        
        @if($extraitem['changing']==true)

            @if ($extraitem['customer_id'] > 0)
                
                <livewire:components.search-livewire name='extra_product_id' 
                :value="$extraitem['product_id']" :weighedlist="1"/>

            @else
                
                <livewire:components.search-livewire key="{{ now() }}"
                name='extra_manufacture_jobcard_product_id' :value="$extraitem['manufacture_jobcard_product_id']"
                :jobid="$dispatch->job_id" :weighedlist="1"/>

            @endif            

        @else
            {{$extraitem['the_description']}}
            @if($overundervariance !== '')
                <br><small class="text-danger"><strong>{{$overundervariance}}</strong></small>            
            @endif            
        @endif
                
    </td>
    <td>{{$extraitem['the_unit']}}</td>
    <td>{{\App\Http\Controllers\Functions::negate($extraitem['the_qty'])}}{{--  of {{\App\Http\Controllers\Functions::negate($extraitem['qty_due'])}} --}}</td>    
    <td>
        
        @if($extraitem['returning']==true)            
            @if (Auth::user()->getSec()->dispatch_returns_value || Auth::user()->getSec()->global_admin_value)
                <x-form.number name="adjust_qty"
                step="0.001" value="{{$extraitem['adjust_qty']}}" />            
                @error('adjust_qty')
                    <small class="text-danger"><strong>{{ $message }}</strong></small>
                @enderror
            @endif
        @elseif($extraitem['transfering']==true)
            @if (Auth::user()->getSec()->dispatch_transfer_request_value || Auth::user()->getSec()->dispatch_transfer_approve_value || Auth::user()->getSec()->global_admin_value)                
                @if($transfer_job_id==0 && $transfer_customer_id==0 && $extraitem['transfer_requested'] != true)
                    @if (Auth::user()->getSec()->dispatch_transfer_request_value || Auth::user()->getSec()->global_admin_value)
                        <div class="radio">
                            <label><input type="radio" name="transfer_to_customer" value=0 checked="{{$transfer_to_customer}}"
                                    wire:model="transfer_to_customer">&nbsp;&nbsp;Transfer to Jobcard</label><br>
                            <label><input type="radio" name="transfer_to_customer" value=1 checked="{{$transfer_to_customer}}"
                                    wire:model="transfer_to_customer">&nbsp;&nbsp;Transfer to Customer</label><br>
                        </div>
                        @if($transfer_to_customer>0)
                            {{-- Transfer to a Customer --}}
                            @if($extraitem['manufacture_jobcard_product_id'] > 0)                
                                <livewire:components.search-livewire name='transfer_customer_id'                
                                manufacturejobcardproductid="{{$extraitem['manufacture_jobcard_product_id']}}"
                                :value="$transfer_customer_id" placeholder="To Customer..."/>
                            @else
                                <livewire:components.search-livewire name='transfer_customer_id'
                                extraitemproductid="{{$extraitem['product_id']}}"   
                                extraitemcustomerid="{{$extraitem['customer_id']}}"                                  
                                :value="$transfer_customer_id" placeholder="To Customer..."/>
                            @endif
                            
                        @else
                            {{-- Transfer to a Jobcard --}}
                            @if($extraitem['manufacture_jobcard_product_id'] > 0)                
                                <livewire:components.search-livewire name='transfer_job_id'
                                manufacturejobcardproductid="{{$extraitem['manufacture_jobcard_product_id']}}"
                                :value="$transfer_job_id" placeholder="To Jobcard..."/>
                            @else
                                <livewire:components.search-livewire name='transfer_job_id'
                                extraitemproductid="{{$extraitem['product_id']}}"
                                extraitemcustomerid="{{$extraitem['customer_id']}}"                    
                                :value="$transfer_job_id" placeholder="To Jobcard..."/>
                            @endif
                        @endif            
                        @error('transfer_job_id')
                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                        @enderror
                        @error('transfer_customer_id')
                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                        @enderror
                    @endif
                @elseif ($extraitem['transfer_requested'] == true)
                    Transfer Req for: {{$extraitem['adjust_qty']}} {{$extraitem['the_unit']}}
                @else
                    @if (Auth::user()->getSec()->dispatch_transfer_request_value || Auth::user()->getSec()->global_admin_value)
                        <x-form.number name="adjust_qty"
                        step="0.001" value="{{$extraitem['adjust_qty']}}" />            
                        @error('adjust_qty')
                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                        @enderror
                    @endif
                @endif            
            @endif
        @endif
    </td>        
    <td>
        @if($dispatchaction == 'new')
            <a wire:click="removeExtraItem('{{$extraitem['id']}}')" class="btn btn-primary btn-sm" title="Remove Item">
                <i class="fas fa-minus"></i>                    
            </a>
        @endif 
                                                                                                                         
        @if($dispatchaction == 'view' && \App\Http\Controllers\Functions::negate($extraitem['the_qty']) > '0')
            @if($extraitem['returning'] == false && $extraitem['qty_due'] < 0 && $extraitem['transfering'] == false && $extraitem['changing'] == false)
                
                @if ((Auth::user()->getSec()->dispatch_admin_value || Auth::user()->getSec()->global_admin_value) && $extraitem['weighed_product']=='1')
                    <a wire:click="startChangingExtraItem"
                        class="btn btn-primary btn-sm" title="Change Item">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                @endif                
            
                @if (Auth::user()->getSec()->dispatch_returns_value || Auth::user()->getSec()->global_admin_value)
                    <a wire:click="startReturnExtraItem('{{$extraitem['id']}}')" class="btn btn-primary btn-sm" title="Return Item">
                        <i class="fas fa-rotate-left"></i>                    
                    </a>
                @endif                
                    
                {{-- This is a jobcard dispatch --}}
                @if (Auth::user()->getSec()->dispatch_transfer_request_value || Auth::user()->getSec()->global_admin_value)
                    <a wire:click="startTransferExtraItem('{{ $extraitem['id'] }}')"
                        class="btn btn-primary btn-sm" title="Transfer Item">
                        <i class="fas fa-right-left"></i>
                    </a>
                @endif
                
                
            @elseif($extraitem['returning'] == true)                
                {{-- R:{{$extraitem['returning']}}Q:{{$extraitem['adjust_qty']}} --}}
                @if (Auth::user()->getSec()->dispatch_returns_value || Auth::user()->getSec()->global_admin_value)
                    <a wire:click="returnExtraItem('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Confirm Return">
                        <i class="fas fa-check"></i>                    
                    </a>                
                    <a wire:click="cancelReturnExtraItem('{{$extraitem['id']}}')" class="btn btn-danger btn-sm" title="Cancel Return">
                        <i class="fas fa-ban"></i>                    
                    </a>
                @endif
            @elseif ($extraitem['transfering'] == true)                

                @if ($extraitem['transfer_requested'] == true)
                    @if (Auth::user()->getSec()->dispatch_transfer_approve_value || Auth::user()->getSec()->global_admin_value)
                        <a wire:click="transferExtraItemConfirm('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Confirm Transfer">
                            <i class="fas fa-check"></i>                    
                        </a>
                    @endif
                @else
                    @if (Auth::user()->getSec()->dispatch_transfer_request_value || Auth::user()->getSec()->global_admin_value)
                        <a wire:click="transferExtraItemRequest('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Request Transfer">
                            <i class="fas fa-check"></i>                    
                        </a>
                    @endif
                @endif

                <a wire:click="cancelTransferExtraItem('{{$extraitem['id']}}')" class="btn btn-danger btn-sm" title="{{Auth::user()->getSec()->dispatch_transfer_request_value ? 'Cancel Transfer':'Decline Transfer'}}">
                    <i class="fas fa-ban"></i>                    
                </a>
                {{-- Header Code --}}
            @elseif ($extraitem['changing'] == true)
                
                <a wire:click="$set('extraitem.changing', false)"
                    class="btn btn-primary btn-sm modal-basic"
                    title="Cancel Changing Item">
                    <i class="fas fa-ban"></i>
                </a>

                {{-- @if ($extraitem['transfer_requested'] == true)
                    @if (Auth::user()->getSec()->dispatch_transfer_approve || Auth::user()->getSec()->global_admin_value)
                        <a wire:click="transferExtraItemConfirm('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Confirm Transfer">
                            <i class="fas fa-check"></i>                    
                        </a>
                    @endif
                @else
                    @if (Auth::user()->getSec()->dispatch_transfer_request || Auth::user()->getSec()->global_admin_value)
                        <a wire:click="transferExtraItemRequest('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Request Transfer">
                            <i class="fas fa-check"></i>                    
                        </a>
                    @endif
                @endif

                <a wire:click="cancelTransferExtraItem('{{$extraitem['id']}}')" class="btn btn-danger btn-sm" title="{{Auth::user()->getSec()->dispatch_transfer_request ? 'Cancel Transfer':'Decline Transfer'}}">
                    <i class="fas fa-ban"></i>                    
                </a> --}}

            @endif
        @else
            {{-- Negative Qty = Transfers / Returns --}}
            @if ($extraitem['status'] == 'Returned'||$extraitem['status'] == 'Partial Return')                
                <a target="_blank" href="{{ url("dispatches/print_return/{$extraitem['dispatch_id']}?extraitemid={$extraitem['id']}&type=return") }}" class="btn btn-primary btn-sm" title="Print Return Note">
                    <i class="fas fa-print"></i>                    
                </a>
            @elseif ($extraitem['status'] == 'Transferred'||$extraitem['status'] == 'Partial Transfer')
                <a target="_blank" href="{{ url("dispatches/print_transfer/{$extraitem['dispatch_id']}?extraitemid={$extraitem['id']}&type=transfer") }}" class="btn btn-primary btn-sm" title="Print Transfer Note">
                    <i class="fas fa-print"></i>                    
                </a>                
            @endif  
        @endif
    </td>    
</tr>
@if(Session::get('print_return'))                        
    <script>
        var $printurl = '{{url("dispatches/print_return/".Session::get('print_return_dispatch_id')."?extraitemid=".Session::get('print_return')."&type=return")}}';
        window.open($printurl.replace('&amp;', '&'),'_blank');
    </script>
    <?php
        session(['print_return' => '']);
    ?>
@endif
@if(Session::get('print_transfer'))                        
    <script>
        var $printurl = '{{url("dispatches/print_transfer/".Session::get('print_transfer_dispatch_id')."?extraitemid=".Session::get('print_transfer')."&type=transfer")}}';
        window.open($printurl.replace('&amp;', '&'),'_blank');
    </script>
    <?php
        session(['print_transfer' => '']);
    ?>
@endif


