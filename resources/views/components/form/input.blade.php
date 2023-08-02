<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label>
    @endif
    <input type="text" class="form-control 
        @if(strlen($class)>0)
            {{$class}}
        @endif
        " 
        name="{{$name}}" 
        value="{{$value}}" 
        @if($wire) 
            wire:model.debounce.500="{{$name}}"
        @endif
        >

        {{-- @error($name)
            <small class="text-danger">{{$message}}</small>
        @enderror --}}
</div>