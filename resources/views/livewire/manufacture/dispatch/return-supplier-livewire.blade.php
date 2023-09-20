<div class="row">
    <div class="col-md-6">
        <x-form.input wire=0 name="registration_number" label="Registration Number" />        
    </div>
    <div class="col-md-6">
        <x-form.select wire=0 name="type_id" label="Supplier" :list="$supplier_list" />
    </div>
    <div class="col-md-6">
        <x-form.select wire=0 name="product_id" label="Products" :list="$products_list" />
    </div>
    <div class="col-md-6">
        <x-form.number wire=0 name="qty" label="Qty" step="0.001" />
    </div>
</div>