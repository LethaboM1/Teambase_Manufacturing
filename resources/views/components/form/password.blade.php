<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label">{{$label}}</label>
    @endif
    <input type="password" class="form-control 
        @if(strlen($class)>0)
            {{$class}}
        @endif
        " 
        name="{{$name}}" 
        value="{{$value}}" 
        @if($wire) 
            wire:model="{{$name}}"
        @endif

        autocomplete="new-password"
        >
</div>