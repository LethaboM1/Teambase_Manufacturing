
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label>
    @endif
    <input type="hidden" 
        @if(strlen($class)>0)
            class="{{$class}}"
        @endif
        name="{{$name}}" 
        value="{{$value}}" 

        @if(strlen($id)>0)
            id="{{ $id }}"
        @endif

        @if($wire) 
            onchange="@this.set('{{$name}}',this.value)"
        @endif
        />