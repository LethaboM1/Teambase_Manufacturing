<div class="@if($class){{$class}}@endif form-check form-switch"> 

    <input class="form-check-input" 
        @if($wire)
            wire:model="{{$name}}"            
        @endif 
        type="checkbox" 
        role="switch" 
        name="{{$name}}"
        id="checked_{{$name}}"
        value="{{$value}}"
        
        @if($checked=='true')     
            checked
        @endif       
    
    >
    
    @if(strlen($label)>0 && strlen($name)>0)
        <label class="form-check-label" for="checked_{{$name}}" id="checked_{{$name}}Label">{{$label}}</label>
    @endif 

</div>