<div>
    <input type="hidden" id="product_id" value="{{$product_id}}" />
    <input wire:model="search" type="text" name="product" class="form-control" placeholder="Product..." value="{{ !empty($product) ? $product['code'].' '.$product['description']: ''}}"/>
    <div class="position-absolute" style="z-index:1000">
        
            @if(!empty($search))
                <ul class="list-group d-flex">
                    @if(!empty($products))
                        @foreach($products as $item)
                            <li wire:click="$set('product_id',{{$item['id']}})" class="list-group-item">{{$item['code']}} {{$item['description']}}</li>      
                        @endforeach
                    @else
                        <li class="list-group-item">Product not found</li>
                    @endif        
                </ul>
            @endif
    </div>
</div>
