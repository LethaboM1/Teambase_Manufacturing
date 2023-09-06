<div class="row">
    <div class="col-md-6">
        <x-form.select name="supplier_id" label="Supplier" :list="$supplier_list" />
    </div>
    <div class="col-md-6">
        <x-form.select name="product_id" label="Products" :list="$products_list" />
    </div>
    
</div>