<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Models\Plants;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AddDispatchModal extends Component
{
    public
        
        $delivery,        
        $weight_in,
        $weight_in_datetime,
        $weight_out,
        $weight_out_datetime,
        $status,
        $plant_id,
        $registration_number,
        $batch_id,
        $qty,
        $delivery_zone,
        $weighed_dispatch;

    function mount()
    {
        
        $this->delivery = 0;
        $this->weighed_dispatch = 0;
        $this->weight_in_datetime = date("Y-m-d\TH:i");
    }

    function updatedJobId($value)
    {
        if ($value > 0) {            
            $this->delivery = $this->jobcard->delivery;            
        }
    }

    function updatedDelivery($value)
    {
        $this->delivery = $value;
    }

    function updatedWeighedDispatch($value)
    {
        $this->weighed_dispatch = $value;
    }

    function boot()
    {
    }

    public function render()
    {
      
        $plant_list = [];
        if ($this->delivery) $plant_list = Plants::select('plant_id as value', DB::raw("concat(plant_number,' ',make,' ',model) as name"))->orderBy('plant_number')->get()->toArray();
        array_unshift($plant_list, ['value' => 0, 'name' => 'Select']);
        

        return view('livewire.manufacture.dispatch.add-dispatch-modal', [            
            'plant_list' => $plant_list,
            'weighed_dispatch' => $this->weighed_dispatch            
        ]);
    }
}
