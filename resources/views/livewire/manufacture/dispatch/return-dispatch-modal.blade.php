<div class="row">
    <div class="col-md-6">
        <x-form.select name="manufacture_jobcard_dispatches_id" label="Dispatch" :list="$manufacture_jobcard_dispatches" />
    </div>
    <div class="col-md-6">
        <x-form.datetime name="weight_in_datetime" label="Date/Time" />
    </div>
    <div class="col-md-6">
        <x-form.number name="weight_in" label="Weight" />
    </div>
    @if($delivery)  
        @if($manufacture_jobcard_dispatches_id>0)
            <div class="col-md-6">
                <h4>{{$dispatch->plant()->plant_number}} {{$dispatch->plant()->make}} {{$dispatch->plant()->model}} {{$dispatch->plant()->registration_number}}</h4>
            </div>            
        @endif
    @else
        @if($manufacture_jobcard_dispatches_id>0)
            <div class="col-md-6">
                <h4>{{$dispatch->registration_number}}</h4>
            </div>
        @endif
    @endif
</div>