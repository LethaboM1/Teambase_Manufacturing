<div class="mb-1">
    @if(strlen($label)>0)
    <label class="form-label col-form-label">{{$label}}</label><br>
    @endif
    <label class='toggle-switch'>
        <input type="checkbox" 
            class="@if(strlen($class)>0) {{$class}} @endif" 
            id="check_{{$idd}}"
            name="{{$name}}" 
            value="{{$value}}" 
            @if($wire) 
                wire:model="{{$name}}"
            @endif
            >
        <span class='toggle-slider round'></span>
    </label>
    {{-- @error($name)
        <small class="text-danger">{{$message}}</small>
    @enderror --}}
</div>
@if($toggle && strlen($idd)>0)
    <script>
        $(document).ready(function () {
         $('#check_{{$idd}}').prop('checked', true);
        });
    </script>
@endif