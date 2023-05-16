<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label>
    @endif
    <input type="file" class="form-control 
        @if(strlen($class)>0)
            {{$class}}
        @endif
        "         name="{{$name}}" 
        @if($wire) 
            wire:model="{{$name}}"
        @endif
        >
        {{-- @error($name)
            <small class="text-danger">{{$message}}</small>
        @enderror --}}
        @if(strlen($path)>0)            
            <a href=""><i class="fa fa-download"></i>&nbsp;Download</a>
        @endif
</div>