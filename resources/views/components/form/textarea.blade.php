<div class="mb-1">
    @if(strlen($label)>0)
        <label for="" class="form-label col-form-label">{{$label}}</label>
    @endif    
    <textarea class="form-control 
    @if(strlen($class)>0)
        {{$class}}
    @endif
    " 
    @if(strlen($rows)>0)
        rows="{{$rows}}"
    @endif

    name="{{$name}}" 
        @if($wire)    
            wire:model.800ms="{{$name}}"
        @endif
        >{{$value}}</textarea>
        {{-- @error($name)
            <small class="text-danger">{{$message}}</small>
        @enderror --}}
</div>