<tr>   
    
    {{-- <td>{{$extraitem['the_date']}}</td> --}}
    <td>
        {{$extraitem['the_description']}}
        @if($overundervariance !== '')
            <br><small class="text-danger"><strong>{{$overundervariance}}</strong></small>            
        @endif        
    </td>
    <td>{{$extraitem['the_unit']}}</td>
    <td>{{\App\Http\Controllers\Functions::negate($extraitem['the_qty'])}}{{--  of {{\App\Http\Controllers\Functions::negate($extraitem['qty_due'])}} --}}</td>    
    <td>
        @if($extraitem['returning']==true)            
            <x-form.number name="adjust_qty"
            step="0.001" value="{{$extraitem['adjust_qty']}}" />            
            @error('adjust_qty')
                <small class="text-danger"><strong>{{ $message }}</strong></small>
            @enderror
        @elseif($extraitem['transfering']==true)
            @if($transfer_job_id==0 && $transfer_customer_id==0)
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
            
            @else
                <x-form.number name="adjust_qty"
                step="0.001" value="{{$extraitem['adjust_qty']}}" />            
                @error('adjust_qty')
                    <small class="text-danger"><strong>{{ $message }}</strong></small>
                @enderror
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
            @if($extraitem['returning'] == false && $extraitem['qty_due'] < 0 && $extraitem['transfering'] == false)
                <a wire:click="startReturnExtraItem('{{$extraitem['id']}}')" class="btn btn-primary btn-sm" title="Return Item">
                    <i class="fas fa-rotate-left"></i>                    
                </a>                
                {{-- <a wire:click="startTransferExtraItem('{{$extraitem['id']}}')" class="btn btn-primary btn-sm" title="Transfer Item">
                    <i class="fas fa-right-left"></i>                    
                </a> --}} {{-- Hidden from User until requested 2023-12-05 --}}
                {{-- @if ($extraitem['manufacture_jobcard_product_id'] > 0)Header Code Transfer available for all dispatches not just Jobs 2024-03-25 --}}
                    {{-- This is a jobcard dispatch --}}
                    <a wire:click="startTransferExtraItem('{{ $extraitem['id'] }}')"
                        class="btn btn-primary btn-sm" title="Transfer Item">
                        <i class="fas fa-right-left"></i>
                    </a>
                {{-- @endif --}} {{-- Header Code --}}
            @elseif($extraitem['returning'] == true)                
                {{-- R:{{$extraitem['returning']}}Q:{{$extraitem['adjust_qty']}} --}}
                <a wire:click="returnExtraItem('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Confirm Return">
                    <i class="fas fa-check"></i>                    
                </a>                
                <a wire:click="cancelReturnExtraItem('{{$extraitem['id']}}')" class="btn btn-danger btn-sm" title="Cancel Return">
                    <i class="fas fa-ban"></i>                    
                </a>
            @elseif ($extraitem['transfering'] == true)
                {{-- T:{{$extraitem['transfering']}}J:{{$extraitem['transfer_job_id']}}Q:{{$extraitem['adjust_qty']}} --}}
                {{-- <a wire:click="confirmTransferExtraItem('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Confirm Transfer">
                    <i class="fas fa-check"></i>                    
                </a>
                <a wire:click="cancelTransferExtraItem('{{$extraitem['id']}}')" class="btn btn-danger btn-sm" title="Cancel Transfer">
                    <i class="fas fa-ban"></i>                    
                </a> --}}{{-- Hidden from User until requested 2023-12-05 --}}

                <a wire:click="transferExtraItem('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Confirm Transfer">
                    <i class="fas fa-check"></i>                    
                </a>
                <a wire:click="cancelTransferExtraItem('{{$extraitem['id']}}')" class="btn btn-danger btn-sm" title="Cancel Transfer">
                    <i class="fas fa-ban"></i>                    
                </a>
                {{-- Header Code --}}

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


