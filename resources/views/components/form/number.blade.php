<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label>
    @endif
    <input type="number" class="form-control @if(strlen($class)>0){{$class}}@endif" 
        @if(strlen($max)>0)
            max="{{ $max }}"
        @endif
        @if(strlen($min)>0)
            min="{{ $min }}"
        @endif
        @if(strlen($step)>0)
            step="{{ $step }}"
        @endif

        name="{{$name}}" 
        value="{{$value}}" 
        @if($wire) 
            wire:model="{{$name}}"
        @endif
        >
        {{-- @error($name)
            <small class="text-danger">{{$message}}</small>
        @enderror --}}
</div>