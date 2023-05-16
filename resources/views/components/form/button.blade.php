<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label>
    @endif
    <a type="@if($submit)
        submit
        @else
        button
        @endif" class="btn 
        @if(strlen($class)>0)
            {{$class}}
        @endif
        " 
        @if(strlen($name)) 
            name="{{$name}}" 
        @endif
        @if(strlen($href)) 
            href="{{$href}}" 
        @endif
        @if(strlen($modal)) 
            data-bs-toggle="modal"
            data-bs-target="#{{ $modal }}"
        @endif

        @if(strlen($wire)) 
            wire:click="{{$wire}}"
        @endif
        >{{$slot}}</a>
</div>