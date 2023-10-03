<div class="row">
    {{-- <div class="col-md-6">
        <x-form.select name="job_id" label="Job card" :list="$jobcard_list" />
    </div>
    <div class="col-md-6">
        <x-form.select name="manufacture_jobcard_product_id" label="Product" :list="$manufacture_jobcard_products_list" />
    </div> 
    <div class="col-md-6">
        <x-form.input name="reference" label="Reference" />
    </div>--}}
    {{-- <div class="col-md-6">
        <x-form.input name="haulier_code" label="Haulier Code" />
    </div>     
    <div class="col-md-6">
        <x-form.datetime name="weight_in_datetime" label="Date/Time" />
    </div>
    //removed 2023-09-13 Marcia
    --}}
    <div class="col-md-6">
        <x-form.number name="weight_in" label="Weight In" step="0.001"/>
    </div>
    {{-- <div class="col-md-6">
        <x-form.toggle idd='delivery' name="delivery" label="Delivery" value={{$delivery}}/>
    </div> --}}
    <div class="col-md-6" style="margin-top: 30px;">
        <x-form.checkbox name="delivery" label="Product is to be Delivered" value=1 />
    </div>
    {{-- Determine if Job is for collection or delivery (Plant assigned or Reg no of Collector assigned) --}}
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