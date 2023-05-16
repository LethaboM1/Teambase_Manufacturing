<div class='mb-1'>
    <div class='form-group'>
        <label>Choose image</label>
        <input type="file" class="form-control 
            @if(strlen($class)>0)
                {{$class}}
            @endif
            "
            name="{{$name}}" 
            >
    </div>
        @if(strlen($path)>0)
        @php
           
            $image = base64_encode(file_get_contents($path));
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            
        @endphp
        <div class="row">
            <div class='form-group'>
                <img class='img img-rounded' width='100%' src='data:image/{{$extension}};base64,{{$image}}'/>
            </div>
        </div>
            
        @endif
    
</div>