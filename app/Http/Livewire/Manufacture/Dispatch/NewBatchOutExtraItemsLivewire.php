<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use Illuminate\Support\Facades\Validator;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcardProductDispatches;

class NewBatchOutExtraItemsLivewire extends Component
{
    public $extraitem, $dispatchaction, $returning, $transfering, $adjust_qty, $transfer_job_id;
    

    public function mount($extraitem, $dispatchaction)
    {
        $this->extraitem = $extraitem;        
        $this->dispatchaction = $dispatchaction;         
        $this->extraitem['returning'] = false;
        $this->extraitem['transfering'] = false;
        $this->extraitem['adjust_qty'] = '0';        
        $this->extraitem['transfer_job_id'] = '0';

    }

    public function messages()
    {
        return [            
            'adjust_qty.lte' => 'The Return Qty is more than the Dispatched Qty.',
            'adjust_qty.gt' => 'The Return Qty must be more than Zero.',
            'transfer_job_id.gt' => 'The New Job Card cannot be Blank.',
            'transfer_job_id.required' => 'The New Job Card cannot be Blank.',
        ];
    }

    public function removeExtraItem ($key){
        $this->emitUp('removeExtraItem', $key);
    }

    public function returnExtraItem ($key){

        $this->validate([
            'adjust_qty' => 'gt:0|lte:'.Functions::negate($this->extraitem['the_qty']) 
        ]);

        $this->emitUp('returnExtraItem', $key, $this->extraitem['adjust_qty']);
    }

    public function transferExtraItem ($key){        

        $this->validate([
            'transfer_job_id' => 'required|gt:0'
        ]);
        $this->emitUp('transferExtraItem', $key, $this->extraitem['adjust_qty'], $this->extraitem['transfer_job_id']);
    }

    public function cancelReturnExtraItem (){
        
        $this->extraitem['returning'] = !$this->extraitem['returning'];
        $this->extraitem['adjust_qty'] = '0';
        
    }

    public function cancelTransferExtraItem (){
        
        $this->extraitem['transfering'] = !$this->extraitem['transfering'];
        $this->extraitem['adjust_qty'] = Functions::negate($this->extraitem['the_qty']);
    }

    public function startReturnExtraItem (){
        
        $this->extraitem['returning'] = !$this->extraitem['returning'];
        $this->extraitem['adjust_qty'] = number_format(Functions::negate($this->extraitem['the_qty']), 3);         
    }

    public function startTransferExtraItem (){
        
        $this->extraitem['transfering'] = !$this->extraitem['transfering'];
        $this->extraitem['adjust_qty'] = Functions::negate($this->extraitem['the_qty']); 
    }

    public function updatedAdjustQty ($value){
                      
        $this->extraitem['adjust_qty'] = $value;
        
    }

    

    public function render()
    {
        
        $dispatch = ManufactureJobcardProductDispatches::where('id', $this->extraitem['dispatch_id'])->first();
        $jobcard_list = [];

        if ($dispatch->product() !== null) {           
            $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
                ->where('status', 'Open')
                ->where('id', '<>', $dispatch->job_id)
                ->whereIn('id', ManufactureJobcardProducts::select('job_id')->where('product_id', $dispatch->product()->id)->get())
                ->get()
                ->toArray();
        } else {

            $jobcard_list = [];

            $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
                ->where('status', 'Open')
                ->get()
                ->toArray();               
        }

        if (count($jobcard_list) > 0) {
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'To Job Card']);
        } else {
            $jobcard_list = [];
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'No Jobcards found...']);
        }
        
        return view('livewire.manufacture.dispatch.new-batch-out-extra-items-livewire', ['jobcard_list' => $jobcard_list]);
    }
}
