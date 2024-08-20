<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Mail\Sms;
use Livewire\Component;
use App\Models\Approvals;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Validator;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureProductTransactions;

class NewBatchOutExtraItemsLivewire extends Component
{
    public $dispatch, $approval, $approval_detail, $extraitem, $dispatchaction, $returning, $transfering, $adjust_qty, $transfer_job_id, $transfer_customer_id, $overundervariance, $transfer_to_customer=0;

    public $listeners = ['emitExtraSet', 'emitSet'];
    

    public function mount($extraitem, $dispatchaction, $overundervariance, $dispatch)
    {
        $this->extraitem = $extraitem;        
        $this->dispatchaction = $dispatchaction;
        $this->dispatch = $dispatch;
        $this->$overundervariance = $overundervariance;        
        $this->extraitem['returning'] = false;
        $this->extraitem['transfering'] = false;
        $this->extraitem['transfer_requested'] = false;
        $this->extraitem['changing'] = false;
        $this->extraitem['adjust_qty'] = '0';        
        $this->extraitem['transfer_job_id'] = '0';
        $this->extraitem['transfer_customer_id'] = '0';        
        //There is an Open Transfer Requested on this Line Item
        if($this->extraitem['status'] == 'Transfer Requested'){
            $this->approval = Approvals::where('request_model', 'manufacture_product_transactions')->where('request_model_id', $this->extraitem['id'])->first()->toArray();
            if(count($this->approval) > 0){
                //Decode Request Array
                $this->approval_detail = base64_decode($this->approval['request_detail_array']);
                $this->approval_detail = json_decode($this->approval_detail, true);
                $this->approval['request']=$this->approval_detail;                

                $this->extraitem['transfering'] = true;
                $this->extraitem['transfer_requested'] = true;
                $this->extraitem['adjust_qty'] = $this->approval['request']['adjust_qty'];        
                $this->extraitem['transfer_job_id'] = $this->approval['request']['transfer_job_id'];
                $this->extraitem['transfer_customer_id'] = $this->approval['request']['transfer_customer_id'];

                $this->adjust_qty = $this->approval['request']['adjust_qty'];        
                $this->transfer_job_id = $this->approval['request']['transfer_job_id'];
                $this->transfer_customer_id = $this->approval['request']['transfer_customer_id'];

                // dd($this->extraitem);
            }
        } 
        // if($this->extraitem['dispatch_id']=='424')dd($this->dispatch);      

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

    function emitSet($var, $value)
    {
        switch ($var) {

            case 'extra_product_id':                
                $this->extraitem['product_id']=$value;

                //Update Line Transaction                          
                ManufactureProductTransactions::whereId($this->extraitem['id'])->update(['product_id'=>$this->extraitem['product_id']]);

                break;              

            case 'extra_manufacture_jobcard_product_id':
                //Check Jobcard filled status on old item
                $old_manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $this->extraitem['manufacture_jobcard_product_id'])->first();
                $new_manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $value)->first();

                //Set new values
                $this->extraitem['manufacture_jobcard_product_id']= $value;
                $this->extraitem['product_id']= ManufactureJobcardProducts::whereId($value)->first()->product_id;

                //Update Line Transaction                
                ManufactureProductTransactions::whereId($this->extraitem['id'])->update(['product_id'=>$this->extraitem['product_id'],
                    'manufacture_jobcard_product_id'=> $this->extraitem['manufacture_jobcard_product_id']]);

                //Check Jobcard Items and update Filled / Open / Closed values
                if($new_manufacture_jobcard_product !== null && $new_manufacture_jobcard_product !== null){
                    //Apply Variance of 500kg/ton on weighed items
                    $product_qty = $new_manufacture_jobcard_product->qty_due;
                    //if ($product_qty == 0) { 2024-02-28 Variances
                    if (($product_qty <= 0.5 && $new_manufacture_jobcard_product->product()->weighed_product > 0)||($product_qty == 0 && $new_manufacture_jobcard_product->product()->weighed_product == 0)) {
                        ManufactureJobcardProducts::where('id', $new_manufacture_jobcard_product->id)->update(['filled' => 1]);
                    }
                    //Apply Variance of 500kg/ton on weighed items
                    $product_qty = $old_manufacture_jobcard_product->qty_due;
                    //if ($product_qty == 0) { 2024-02-28 Variances
                    if (($product_qty <= 0.5 && $old_manufacture_jobcard_product->product()->weighed_product > 0)||($product_qty == 0 && $old_manufacture_jobcard_product->product()->weighed_product == 0)) {
                        ManufactureJobcardProducts::where('id', $old_manufacture_jobcard_product->id)->update(['filled' => 1]);
                    }
                    
                    //Set job card as Filled if filled <> 0
                    if (ManufactureJobcardProducts::where('job_id', $new_manufacture_jobcard_product->jobcard()->id)->where('filled', '0')->count() == 0) {

                        ManufactureJobcards::where('id', $new_manufacture_jobcard_product->jobcard()->id)->update(['status' => 'Filled']);
                    }

                    //Set job card as Filled if filled <> 0
                    if (ManufactureJobcardProducts::where('job_id', $old_manufacture_jobcard_product->jobcard()->id)->where('filled', '0')->count() == 0) {

                        ManufactureJobcards::where('id', $old_manufacture_jobcard_product->jobcard()->id)->update(['status' => 'Filled']);
                    }
                }     

                //Set job card as Open if filled <> 1
                if (ManufactureJobcardProducts::where('job_id', $this->dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {
                            
                    ManufactureJobcards::where('id', $this->dispatch->jobcard()->id)->update(['status' => 'Open']);
                }
                
                
                break;    
        }
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

    public function transferExtraItemRequest ($key){
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
            //Update Status to Transfer Requested and Insert Approval Request.
            $this->emitUp('transferExtraItemRequest', $key, $this->extraitem['adjust_qty'], 0, $this->extraitem['transfer_job_id'], $new_job_product->id);

        } elseif($this->extraitem['transfer_customer_id'] > 0) {            
            $this->validate([
                'transfer_customer_id' => 'required|gt:0',
                'adjust_qty' => 'gt:0|lte:'.Functions::negate($this->extraitem['qty_due'])
            ]);
            //Update Status to Transfer Requested and Insert Approval Request.            
            $this->emitUp('transferExtraItemRequest', $key, $this->extraitem['adjust_qty'], $this->extraitem['transfer_customer_id'], 0, 0);
        }
        
    }

    public function transferExtraItemConfirm ($key){
        // dd($this->extraitem);        
        if($this->extraitem['transfer_requested'] == 'true'){
            
            //Approved by Approval User
            Approvals::whereId($this->approval['id'])->update(['declined'=>'0', 'approved'=>'1', 'approving_user_id' => Auth::user()->user_id]);            
            
            //Update Extra Item Status
            $this->extraitem['transfer_requested'] = false;
            ManufactureProductTransactions::whereId($this->extraitem['id'])->where('Status', 'Transfer Requested')->update(['Status'=>'Dispatched']);
        }

        //Transfer & Return Notes changes 2024-03-12
        if($this->extraitem['transfer_job_id'] > 0){
            //Get Amnt Due on target Jobcard
            $new_job_product = ManufactureJobcardProducts::where('job_id', $this->extraitem['transfer_job_id'])
            ->where('product_id', $this->extraitem['product_id'])->first();            
            $this->validate([
                'transfer_job_id' => 'required|gt:0',
                'adjust_qty' => 'gt:0|lte:'.Functions::negate($this->extraitem['qty_due']).'|lte:'.$new_job_product->qty_due
            ]);            
            $this->emitUp('transferExtraItemConfirm', $key, $this->extraitem['adjust_qty'], 0, $this->extraitem['transfer_job_id'], $new_job_product->id);
        } elseif($this->extraitem['transfer_customer_id'] > 0) {            
            $this->validate([
                'transfer_customer_id' => 'required|gt:0',
                'adjust_qty' => 'gt:0|lte:'.Functions::negate($this->extraitem['qty_due'])
            ]);            
            $this->emitUp('transferExtraItemConfirm', $key, $this->extraitem['adjust_qty'], $this->extraitem['transfer_customer_id'], 0, 0);
        }        
        
    }

    public function cancelReturnExtraItem (){
        
        $this->extraitem['returning'] = !$this->extraitem['returning'];
        $this->extraitem['adjust_qty'] = '0';
        
    }

    public function cancelTransferExtraItem (){
        
        $this->extraitem['transfering'] = !$this->extraitem['transfering'];
        if($this->extraitem['transfer_requested'] == 'true'){
            //Cancel/Delete Approval Request
            if ($this->approval['requesting_user_id']==Auth::user()->user_id){
                //Cancelled by Requesting user
                Approvals::whereId($this->approval['id'])->delete();
            } else {
                //Declined by Approval User
                Approvals::whereId($this->approval['id'])->update(['declined'=>'1', 'approved'=>'0', 'approving_user_id' => Auth::user()->user_id]);
            }
            
            //Update Extra Item Status
            $this->extraitem['transfer_requested'] = false;
            ManufactureProductTransactions::whereId($this->extraitem['id'])->where('Status', 'Transfer Requested')->update(['Status'=>'Dispatched']);
        }        
        
        $this->extraitem['adjust_qty'] = Functions::negate($this->extraitem['qty_due']);
        $this->extraitem['adjust_qty'] = '0';
        $this->transfer_job_id = '0';
        $this->transfer_customer_id = '0';
    }

    public function startChangingExtraItem(){        
        $this->extraitem['changing'] = !$this->extraitem['changing'];
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
