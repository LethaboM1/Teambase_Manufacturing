<div class="row">
    <div class="col-md-6">
        <x-form.input name="reference_number" label="Deliver Number" />
    </div>
    <div class="col-md-6">
        <x-form.input name="registration_number" label="Registration Number" />        
    </div>
    <div class="col-md-6">
        <x-form.select wire=0 name="supplier_id" label="Supplier" :list="$supplier_list" />
    </div>
    <div class="col-md-6">
        <x-form.select wire=0 name="product_id" label="Products" :list="$products_list" />
    </div>
    <div class="col-md-6">
        <x-form.datetime wire=0 name="weight_in_datetime" label="Date Time" :value="date('Y-m-d\TH:i')" />
    </div>
    <div class="col-md-6">
        <x-form.number wire=0 name="weight_in" label="Weight" />
    </div>
</div>