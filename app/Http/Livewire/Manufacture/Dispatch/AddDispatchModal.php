<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Models\ManufactureJobcardProductDispatches;
use App\Models\Plants;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AddDispatchModal extends Component
{
    public

        $delivery,
        $use_historical_weight_in,
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
        $outsourced_transport,
        $outsourced_contractor;

    protected $listeners = ['emitSet'];

    function emitSet($var, $value)
    {
        switch ($var) {
            case 'plant_id':
                $this->plant_id = $value;                
                break;            
        }
    }

    function refreshNewDispatchModal (){        
        //Reload Modal        
        return redirect(request()->header('Referer'));
    }

    function mount()
    {

        $this->delivery = 0;
        $this->use_historical_weight_in = 0;
        $this->outsourced_transport = 0;
        $this->weight_in_datetime = date("Y-m-d\TH:i");
    }

    // function updatedJobId($value)
    // {
    //     if ($value > 0) {
    //         $this->delivery = $this->jobcard->delivery;
    //     }
    // }

    function updatedDelivery($value)
    {
        $this->delivery = $value;
        if($this->delivery != 1){
            //Clear values if not delivered
            $this->plant_id = '';            
            $this->outsourced_contractor = '';
            $this->outsourced_transport = 0;
            $this->use_historical_weight_in = 0;
            $this->weight_in = '0.000';
        }
    } 
    
    function updatedOutsourcedTransport($value)
    {
        $this->outsourced_transport = $value;
        if($this->outsourced_transport != 1){
            //Clear values if not delivered
                       
            $this->outsourced_contractor = '';            
            $this->use_historical_weight_in = 0;
            $this->weight_in = '0.000';
        } else {
            $this->plant_id = ''; 
        }
    }

    function updatedUseHistoricalWeightIn($value)
    {
        $this->use_historical_weight_in = $value; 
        //Amend Weight-In from most recent live weigh in data
        if($this->use_historical_weight_in == '1' && $this->plant_id != ''){
            
            $dispatches = ManufactureJobcardProductDispatches::select('weight_in')
            ->where('use_historical_weight_in', '0')
            ->where('plant_id', $this->plant_id)
            ->where('status', '<>', 'Loading')
            ->where('weight_in', '>', 0)
            ->orderBy('weight_in_datetime', 'desc')
            ->first();            

            if (!is_null($dispatches)){
                $this->weight_in = $dispatches->weight_in;
            } else {
                $this->weight_in = '0.000';
            }
            
        } else {
            $this->weight_in = '0.000';
        }        
    }

    public function render()
    {

        $plant_list = [];
        if ($this->delivery) $plant_list = Plants::select('plant_id as value', DB::raw("concat(plant_number,' ',make,' ',model) as name"))->orderBy('plant_number')->get()->toArray();
        array_unshift($plant_list, ['value' => 0, 'name' => 'Select']); 
        
        $outsourced_contractors_list = ManufactureJobcardProductDispatches::select('outsourced_contractor as value')
        ->distinct()
        ->orderBy('outsourced_contractor', 'asc') 
        ->where('outsourced_contractor','<>','')
        ->whereNotNull('outsourced_contractor')       
        ->get()
        ->toArray();                  

        return view('livewire.manufacture.dispatch.add-dispatch-modal', [
            'plant_list' => $plant_list,
            'outsourced_contractors_list' => $outsourced_contractors_list,
            // 'weighed_dispatch' => $this->weighed_dispatch
        ]);
    }
}
