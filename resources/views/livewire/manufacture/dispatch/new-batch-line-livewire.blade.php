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
        {{ $dispatch->plant() !== null ? 
            "{$dispatch->plant()->plant_number}-{$dispatch->plant()->make}-{$dispatch->plant()->reg_number}"
            : ($dispatch->outsourced_contractor !== '' && $dispatch->customer_id !== '0'  ?  
                "{$dispatch->outsourced_contractor}-{$dispatch->registration_number}"
                : $dispatch->registration_number)
        }}
    </td>

    <td onclick="$('#edit_btn_{{ $dispatch->id }}').click()">{{ $dispatch->status }}</td>
    <td style="width:100px">
        @if ($dispatchaction == 'new')
            @if (Auth::user()->getSec()->getCRUD('dispatch_crud')['create'] || Auth::user()->getSec()->global_admin_value)
                <a id="edit_btn_{{ $dispatch->id }}" href="#editDispatch_{{ $dispatch->id }}"
                    class="btn btn-primary btn-sm modal-basic" title="Process Loading Dispatch">
                    <i class="fas fa-edit"></i>
                </a>
            @endif
        @else
            <a id="edit_btn_{{ $dispatch->id }}" href="#editDispatch_{{ $dispatch->id }}"
                class="btn btn-primary btn-sm modal-basic" title="View Archived Dispatch">
                <i class="fas fa-eye"></i>
            </a>          
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

    </td>
</tr>
