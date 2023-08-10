<div class="mb-1">
    @if(strlen($label)>0)
        <label class="form-label">{{$label}}</label>
    @endif
    <input type="datetime-local" class="form-control form-control-sm
        @if(isset($class) && strlen($class)>0)
            {{$class}}
        @endif
        " 
        name="{{$name}}" 
        @if(strlen($value)>0)
        value="{{$value}}" 
        @endif

        @if($wire) 
            wire:model.debounce.700ms="{{$name}}"
        @endif
        >
        {{-- @error($name)
            <small class="text-danger">{{$message}}</small>
        @enderror --}}
</div>