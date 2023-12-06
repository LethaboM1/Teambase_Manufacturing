
<tr>   
    <td>{{$extraitem['the_date']}}</td>
    <td>{{$extraitem['the_description']}}</td>
    <td>{{$extraitem['the_unit']}}</td>
    <td>{{\App\Http\Controllers\Functions::negate($extraitem['the_qty'])}}</td>
    
    <td>
        @if($extraitem['returning']==true)            
            <x-form.number name="adjust_qty"
            step="0.001" value="{{$extraitem['adjust_qty']}}" />
            @error('adjust_qty')
                <small class="text-danger"><strong>{{ $message }}</strong></small>
            @enderror
        @elseif($extraitem['transfering']==true)
            <x-form.select name="transfer_job_id" :list="$jobcard_list" />            
            @error('transfer_job_id')
                <small class="text-danger"><strong>{{ $message }}</strong></small>
            @enderror
        @endif        
    <td>
        @if($dispatchaction == 'new')
            <a wire:click="removeExtraItem('{{$extraitem['id']}}')" class="btn btn-primary btn-sm" title="Remove Item">
                <i class="fas fa-minus"></i>                    
            </a>
        @endif                                                                                                                   
        @if($dispatchaction == 'view' && \App\Http\Controllers\Functions::negate($extraitem['the_qty']) > '0')
            @if($extraitem['returning'] == false && $extraitem['transfering'] == false)
                <a wire:click="startReturnExtraItem('{{$extraitem['id']}}')" class="btn btn-primary btn-sm" title="Return Item">
                    <i class="fas fa-rotate-left"></i>                    
                </a>
                {{-- <a wire:click="startTransferExtraItem('{{$extraitem['id']}}')" class="btn btn-primary btn-sm" title="Transfer Item">
                    <i class="fas fa-right-left"></i>                    
                </a> --}} {{-- Hidden from User until requested 2023-12-05 --}}
            @elseif($extraitem['returning'] == true)                
                <a wire:click="returnExtraItem('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Confirm Return">
                    <i class="fas fa-check"></i>                    
                </a>                
                <a wire:click="cancelReturnExtraItem('{{$extraitem['id']}}')" class="btn btn-danger btn-sm" title="Cancel Return">
                    <i class="fas fa-ban"></i>                    
                </a>
            @elseif ($extraitem['transfering'] == true)
                {{-- <a wire:click="transferExtraItem('{{$extraitem['id']}}')" class="btn btn-success btn-sm" title="Confirm Transfer">
                    <i class="fas fa-check"></i>                    
                </a>
                <a wire:click="cancelTransferExtraItem('{{$extraitem['id']}}')" class="btn btn-danger btn-sm" title="Cancel Transfer">
                    <i class="fas fa-ban"></i>                    
                </a> --}}{{-- Hidden from User until requested 2023-12-05 --}}
            @endif
        @endif
    </td>
</tr>