<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label>
    @endif
    <select class="form-control
        @if(strlen($class)>0)
            {{$class}}
        @endif
        "         
        name="{{$name}}"
        @if($wire) 
            wire:model.lazy="{{$name}}"
        @endif
        >
        @foreach($list as $select_item)            
            <option @if($select_item['value'] == $value) selected="selected" @endif value="{{$select_item['value']}}">{{$select_item['name']}}</option>            
        @endforeach
    </select>
    {{-- @error($name)
        <small class="text-danger">{{$message}}</small>
    @enderror --}}
</div>