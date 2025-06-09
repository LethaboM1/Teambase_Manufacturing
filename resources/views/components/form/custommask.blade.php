<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label>
    @endif
    <input type="text"   
    
    class="form-control 
        @if(strlen($class)>0)
            {{$class}}
        @endif
        " name="{{$name}}" value="{{$value}}"
        @if($wire)             
            wire:model="{{$name}}" onchange="@this.set('{{$name}}',this.value)" 
        @endif>
        {{-- @error($name)
            <small class="text-danger">{{$message}}</small>
        @enderror --}}
        <script>
            $(document).ready(function () { 
                /* Mask Settings */
                Inputmask("{{$themask}}", {
                    placeholder: "{{$themaskplaceholder}}",
                    greedy: false,                    
                    // jitMasking: true
                }).mask($('input[name="{{$name}}"]'));
            });	
        </script>
</div>