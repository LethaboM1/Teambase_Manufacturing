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
        list="{{$name}}_datalist"
        autocomplete="off"
        placeholder="{{$placeholder}}"

        @if($disabled)
            disabled="{{$disabled}}" 
        @endif
        @if($wire) 
            wire:model.debounce.500="{{$name}}"
        @endif                
        >
    <datalist id="{{$name}}_datalist">
        @foreach($list as $select_item)            
            <option @if($select_item['value'] == $value) selected="selected" @endif value="{{$select_item['value']}}">            
        @endforeach
    </datalist>

        {{-- @error($name)
            <small class="text-danger">{{$message}}</small>
        @enderror --}}
</div>