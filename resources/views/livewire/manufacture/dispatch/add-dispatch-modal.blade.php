<div class="row">

    {{-- Determine of this is a weighed delivery / collection --}}
    {{-- <div class="col-md-10" style="margin-top: 30px;">
        <x-form.checkbox name="weighed_dispatch" label="Collecting / Delivery vehicle has been Weighed In." value=1 />
    </div> --}}

    {{-- @if ($weighed_dispatch) --}}
    <div class="col-md-6">
        <x-form.number name="weight_in" label="Weight In" step="0.001" />
    </div>

    <div class="col-md-6">
        <x-form.datetime name="weight_in_datetime" label="Date/Time" />
    </div>
    {{-- @else
        <div class="col-md-6">
            <x-form.hidden name="weighed_dispatch" value={{$weighed_dispatch}}/>
        </div>
    @endif --}}


    <div class="col-md-10" style="margin-top: 30px;">
        <x-form.checkbox name="delivery" label="Product is to be Delivered" value=1 />
    </div>

    {{-- Determine if Job is for collection or delivery (Plant assigned or Reg no of Collector assigned) --}}
    @if ($delivery)
        <div class="col-md-6">
            <livewire:components.search-livewire name='plant_id' label="Plant" :value="$plant_id" />
            {{-- <x-form.select name="plant_id" label="Plant" :list="$plant_list"/> --}}
        </div>
    @else
        <div class="col-md-6">
            <x-form.input name="registration_number" label="Reg No." />
        </div>
    @endif
</div>
