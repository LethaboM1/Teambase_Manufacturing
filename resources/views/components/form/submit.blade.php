<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label>
    @endif
    <input type="submit" class="btn 
        @if(strlen($class)>0)
            {{$class}}
        @endif
        " 
        name="{{$name}}" 
        value="{{$value}}" 
        @if(strlen($wire)) 
            wire:click="{{$wire}}"
        @endif
        @if($disabled)
            disabled
        @endif
        >
</div>