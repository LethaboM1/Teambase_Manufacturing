<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcardProductDispatches;

class NewBatchLineLivewire extends Component
{
    public $dispatch, $weight_out, $weight_out_datetime, $qty, $dispatchaction, $weight_in_datetime, $weight_in, $dispatch_temp,
        $job_id,
        $jobcard,
        $manufacture_jobcard_product_id,
        $haulier_code,
        $delivery,
        $comment,
        $status,
        $plant_id,
        $registration_number,
        $batch_id,        
        $delivery_zone;

    function mount($dispatch)
    {
        $this->dispatch = $dispatch;
        $this->weight_out_datetime = date("Y-m-d\TH:i");
        $this->weight_out = 0;
        $this->dispatch_temp = 0;
        $this->qty = 0;
        //for returns
        $this->weight_in_datetime = date("Y-m-d\TH:i");
        $this->weight_in = 0;
    }    

    function updatedWeightOut($value)
    {
        if ($value < $this->dispatch->weight_in) return;
        $this->qty = $value - $this->dispatch->weight_in;
    }

    public function render()
    {
        //List Open Jobcards with Product Due
        $jobcard_list = [];
        $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',contractor,', ',contact_person) as name"))
            ->where('status', 'Open')
            ->where('jobcard_number', '<>', $this->dispatch->jobcard()->jobcard_number)
            ->whereIn('id', ManufactureJobcardProducts::select('job_id')->where('product_id', $this->dispatch->product()->id)->get())                        
            ->get()
            ->toArray();
               
        
        if (count($jobcard_list) > 0) {           

            array_unshift($jobcard_list, ['value' => 0, 'name' => 'Select']);

        } else {
            $jobcard_list = [];
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'No Jobcards found...']);            
        }       
        
        
        //List of Delivery Zones
        $delivery_zone_list = $delivery_zone_list = SelectLists::zones_select; //ManufactureJobcardProductDispatchDeliveryzones::select('code as value', DB::raw("description as name"))->get()->toArray();
        array_unshift($delivery_zone_list, ['value' => 0, 'name' => 'Select']);
        
        return view('livewire.manufacture.dispatch.new-batch-line-livewire', ['delivery_zone_list' => $delivery_zone_list,
        'jobcard_list' => $jobcard_list]);
    }
}
