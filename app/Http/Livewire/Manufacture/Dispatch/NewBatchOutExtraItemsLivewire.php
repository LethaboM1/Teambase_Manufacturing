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
    public $extraitem, $dispatchaction, $returning, $transfering, $adjust_qty, $transfer_job_id, $transfer_customer_id, $overundervariance, $transfer_to_customer=0;

    public $listeners = ['emitExtraSet'];
    

    public function mount($extraitem, $dispatchaction, $overundervariance)
    {
        $this->extraitem = $extraitem;        
        $this->dispatchaction = $dispatchaction;
        $this->$overundervariance = $overundervariance;
        $this->extraitem['returning'] = false;
        $this->extraitem['transfering'] = false;
        $this->extraitem['adjust_qty'] = '0';        
        $this->extraitem['transfer_job_id'] = '0';
        $this->extraitem['transfer_customer_id'] = '0';

    }

    public function messages()
    {
        return [            
            'adjust_qty.lte' => 'The Return / Transfer Qty is more than the Dispatched Qty or more than New Jobcard due Qty.',
            'adjust_qty.gt' => 'The Return / Transfer Qty must be more than Zero.',
            'transfer_job_id.gt' => 'The New Job Card cannot be Blank.',
            'transfer_job_id.required' => 'The New Job Card cannot be Blank.',
            'transfer_customer_id.gt' => 'The New Customer cannot be Blank.',
            'transfer_customer_id.required' => 'The New Customer cannot be Blank.',
        ];
    }

    function emitExtraSet($var, $value)
    {        
        // dd('var:'.$var.', value:'.$value);
        switch ($var) {            
            case 'transfer_job_id':                                
                $this->transfer_job_id = $value;
                self::updatedTransferJobId($value);
                break; 

            case 'transfer_customer_id':                                
                $this->transfer_customer_id = $value;
                self::updatedTransferCustomerId($value);
                break;    
        }
    }

    public function removeExtraItem ($key){
        $this->emitUp('removeExtraItem', $key);
    }

    public function returnExtraItem ($key){

        //Transfer & Return Notes changes 2024-03-12
        $this->validate([
            'adjust_qty' => 'gt:0|lte:'.Functions::negate($this->extraitem['qty_due']) 
        ]);

        $this->emitUp('returnExtraItem', $key, $this->extraitem['adjust_qty']);
    }

    public function transferExtraItem ($key){
// dd($this->extraitem);        
        //Transfer & Return Notes changes 2024-03-12
        if($this->extraitem['transfer_job_id'] > 0){
            //Get Amnt Due on target Jobcard
            $new_job_product = ManufactureJobcardProducts::where('job_id', $this->extraitem['transfer_job_id'])
            ->where('product_id', $this->extraitem['product_id'])->first();            
            $this->validate([
                'transfer_job_id' => 'required|gt:0',
                'adjust_qty' => 'gt:0|lte:'.Functions::negate($this->extraitem['qty_due']).'|lte:'.$new_job_product->qty_due
            ]);            
            $this->emitUp('transferExtraItem', $key, $this->extraitem['adjust_qty'], 0, $this->extraitem['transfer_job_id'], $new_job_product->id);
        } elseif($this->extraitem['transfer_customer_id'] > 0) {            
            $this->validate([
                'transfer_customer_id' => 'required|gt:0',
                'adjust_qty' => 'gt:0|lte:'.Functions::negate($this->extraitem['qty_due'])
            ]);            
            $this->emitUp('transferExtraItem', $key, $this->extraitem['adjust_qty'], $this->extraitem['transfer_customer_id'], 0, 0);
        }
        
    }

    public function cancelReturnExtraItem (){
        
        $this->extraitem['returning'] = !$this->extraitem['returning'];
        $this->extraitem['adjust_qty'] = '0';
        
    }

    public function cancelTransferExtraItem (){
        
        $this->extraitem['transfering'] = !$this->extraitem['transfering'];
        $this->extraitem['adjust_qty'] = Functions::negate($this->extraitem['qty_due']);
        $this->extraitem['adjust_qty'] = '0';
        $this->transfer_job_id = '0';
        $this->transfer_customer_id = '0';
    }

    public function startReturnExtraItem (){
        
        $this->extraitem['returning'] = !$this->extraitem['returning'];
        $this->extraitem['adjust_qty'] = number_format(Functions::negate($this->extraitem['qty_due']), 3);              
    }

    public function startTransferExtraItem (){
        
        $this->extraitem['transfering'] = !$this->extraitem['transfering'];
        $this->extraitem['adjust_qty'] = number_format(Functions::negate($this->extraitem['qty_due']), 3); 
    }

    public function updatedAdjustQty ($value){
                      
        $this->extraitem['adjust_qty'] = $value;
        
    }

    public function updatedTransferJobId ($value){   

        $this->extraitem['transfer_job_id'] = $value;
        $this->extraitem['transfer_customer_id'] = '0';        

    }    

    public function updatedTransferCustomerId ($value){   

        $this->extraitem['transfer_customer_id'] = $value;        
        $this->extraitem['transfer_job_id'] = '0';

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
                /* $jobcard_list = ManufactureJobcards::select(DB::raw("concat('[', id, '] ',jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as value"))
                ->where('status', 'Open')
                ->where('id', '<>', $dispatch->job_id)
                ->whereIn('id', ManufactureJobcardProducts::select('job_id')->where('product_id', $dispatch->product()->id)->get())
                ->get()
                ->toArray(); */
                // dd('filtered list');
        } else {

            $jobcard_list = [];

            $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
                ->where('status', 'Open')
                ->get()
                ->toArray();     
                /* $jobcard_list = ManufactureJobcards::select(DB::raw("concat('[', id, '] ',jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as value"))
                ->where('status', 'Open')
                ->get()
                ->toArray(); */           
                // dd('full list');
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
