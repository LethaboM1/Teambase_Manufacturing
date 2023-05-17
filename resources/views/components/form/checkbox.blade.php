<div class="checkbox-custom checkbox-default">
    
        <input type="checkbox" id="{{$name}}" class="
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
        <label for="{{$name}}">{{$label}}</label>
    @endif    
</div>