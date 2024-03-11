<tr class="pointer">
    <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">{{ $dispatch->weight_in_datetime }}</td>
    <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">{{ $dispatch->dispatch_number }}</td>
    <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">{{ $dispatch->reference }}</td>
    <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">
        {{ $dispatch->jobcard() !== null ? $dispatch->jobcard()->jobcard_number : '' }}</td>
    <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">
        {{ $dispatch->jobcard() !== null ? $dispatch->jobcard()->site_number : '' }}</td>    
    @if ($dispatch->status !== 'Loading')

        @if ($dispatch->jobcard() !== null)
            <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">{{ $dispatch->jobcard()->contractor }}</td>
        @else
            <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">
                {{ $dispatch->customer() !== null ? $dispatch->customer()->name : 'None' }}</td>
        @endif
    @else
        <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()"></td>
    @endif

    <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">
        {{ $dispatch->plant() !== null ? "{$dispatch->plant()->plant_number}-{$dispatch->plant()->make}-{$dispatch->plant()->reg_number}" : ($dispatch->outsourced_contractor !== '' && $dispatch->customer_id !== '0'  ?  "{$dispatch->outsourced_contractor}-{$dispatch->registration_number}" : $dispatch->registration_number)}}
    </td>

    <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">{{ $dispatch->status }}</td>
    <td style="width:100px">
        @if ($dispatchaction == 'new')
            <a id="edit_btn_{{ $dispatch->id }}" href="#editDispatch_{{ $dispatch->id }}"
                class="btn btn-primary btn-sm modal-basic" title="Process Loading Dispatch">
                <i class="fas fa-edit"></i>
            </a>
        @else
            <a id="edit_btn_{{ $dispatch->id }}" href="#editDispatch_{{ $dispatch->id }}"
                class="btn btn-primary btn-sm modal-basic" title="View Archived Dispatch">
                <i class="fas fa-eye"></i>
            </a>
            {{-- @if ($dispatch->qty !== '0.000')
                <a id="return_btn_{{$dispatch->id}}" href="#returnDispatch_{{$dispatch->id}}" class="btn btn-primary btn-sm modal-basic" title="Return Product on this Dispatch">
                    <i class="fas fa-rotate-left"></i>                    
                </a>
                <a id="transfer_btn_{{$dispatch->id}}" href="#transferDispatch_{{$dispatch->id}}" class="btn btn-primary btn-sm modal-basic" title="Transfer Product on this Dispatch to another Jobcard">
                    <i class="fas fa-right-left"></i>                    
                </a>
            @endif --}} {{-- Moved to Lines Level 2023-12-01 --}}
        @endif
        <div id='editDispatch_{{ $dispatch->id }}' class='modal-block modal-block-lg mfp-hide'>
            <livewire:manufacture.dispatch.new-batch-out-modal :dispatch="$dispatch" :dispatchaction="$dispatchaction">
                {{-- Refresh listeners on Modals --}}
                <script>
                    setTimeout(function() {
                        $.getScript('{{ url('js/examples/examples.modals.js') }}');
                    }, 500);
                </script>
        </div>

        {{-- <div id='returnDispatch_{{$dispatch->id}}' class='modal-block modal-block-lg mfp-hide'>           
            <livewire:manufacture.dispatch.return-batch-modal :dispatch="$dispatch" :dispatchaction="$dispatchaction"/>
        </div> --}} {{-- Obsolete - Returns / Transfer on Batch Out Modal on line item level 2023-12-05 --}}

        {{-- <div id='transferDispatch_{{$dispatch->id}}' class='modal-block modal-block-lg mfp-hide'>           
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
        </div> --}} {{-- Obsolete - Returns / Transfer on Batch Out Modal on line item level 2023-12-05 --}}


    </td>
</tr>
