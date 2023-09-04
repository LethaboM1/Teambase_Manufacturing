<div class="row">
    <div class="col-md-6">
        <x-form.select name="job_id" label="Job card" :list="$jobcard_list" />
    </div>
    <div class="col-md-6">
        <x-form.select name="manufacture_jobcard_product_id" label="Product" :list="$manufacture_jobcard_products_list" />
    </div>
    <div class="col-md-6">
        <x-form.input name="reference" label="Reference" />
    </div>
    <div class="col-md-6">
        <x-form.input name="haulier_code" label="Haulier Code" />
    </div>
    <div class="col-md-6">
        <x-form.datetime name="weight_in_datetime" label="Date/Time" />
    </div>
    <div class="col-md-6">
        <x-form.number name="weight_in" label="Weight In" />
    </div>
    @if($delivery)  
        <div class="col-md-6">
            <x-form.select name="plant_id" label="Plant" :list="$plant_list"/>
        </div>
    @else
    <div class="col-md-6">
        <x-form.input name="registration_number" label="Reg No." />
    </div>
    @endif
</div>