<div class="mb-1">
    @if(strlen($label)>0)
        <label class="form-label col-form-label">{{$label}}</label>
    @endif    
    <input type="text"
        {{-- data-inputmask=" 'alias': 'decimal', 'groupSeparator': '', 'autoGroup': true, 'rightAlign' : false, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'" --}}
        class="form-control mask-money
        @if(strlen($class)>0)
            {{$class}}
        @endif
        " name="{{$name}}" 
        value="{{$value}}"
        @if($wire)            
            wire:model="{{$name}}"
            {{-- onchange="@this.set('{{$name}}',this.value)" --}}
        @endif
        >
        {{-- @error($name)
            <small class="text-danger">{{$message}}</small>
        @enderror --}}
</div>