<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">
    @endif
        <input type="checkbox" class="
            @if(strlen($class)>0)
                {{$class}}
            @endif
            " 
            name="{{$name}}" 
            @if($toggle)
                checked="checked"
            @endif
            value="{{$value}}" 
            @if($wire) 
                wire:model="{{$name}}"
            @endif
            >        
    @if(strlen($label)>0)
        &nbsp;{{$label}}
    </label>
    @endif
    {{-- @error($name)
        <small class="text-danger">{{$message}}</small>
    @enderror --}}
</div>