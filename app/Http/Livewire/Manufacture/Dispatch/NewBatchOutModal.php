<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Models\ManufactureCustomers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SelectLists;
use App\Models\Approvals;

use function PHPUnit\Framework\isTrue;

use function PHPUnit\Framework\isFalse;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureProductTransactions;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\User;

class NewBatchOutModal extends Component
{
    public $dispatch, $weight_out_datetime, $weight_out, $over_under_variance = [], $dispatch_temp, $dispatchaction, $qty, $qty_due, $job_id, $weight_in_datetime, $weight_in;
    public $jobcard, $delivery, $delivery_zone, $reference, $manufacture_jobcard_product_id/* , $extra_manufacture_jobcard_product_id */;
    public $customer_dispatch, $customer_id, $product_id;
    public $add_extra_item_show = false, $extra_product_id, $extra_product_weighed, $extra_product_unit_measure, $extraproduct, $extra_product_qty, $extra_product_weight_in_date, $extra_item_message, $extra_item_error;
    public $dispatch_adjust_qty, $dispatch_return_weight_in, $return_item_success, $return_item_message, $return_extraitem_success, $return_extraitem_message, $weighedlist, $return_extraitem_qty;
    public $dispatch_transfer_weight_in, $transfer_item_success, $transfer_item_message, $transfer_extraitem_success, $transfer_extraitem_message, $transfer_job_id;
    public $changingReferenceNo;

    public $listeners = ['removeExtraItem', 'returnExtraItem', 'transferExtraItemRequest', 'transferExtraItemConfirm', 'addExtraItem', 'emitSet'];


    function emitSet($var, $value)
    {
        switch ($var) {

            case 'customer_id':
                $this->customer_id = $value;
                self::updatedCustomerId($value);
                break;


            case 'job_id':
                $this->job_id = $value;
                self::updatedJobId($value);
                $this->manufacture_jobcard_product_id = 0;

                break;

            case 'transfer_job_id':
                $this->transfer_job_id = $value;
                break;

            case 'product_id':
                $this->product_id = $value;
                self::updatedProductId($value);
                break; //Moved to Lines 2024-03-15

            case 'extra_product_id':
                $this->extra_product_id = $value;
                self::updatedExtraProductId($value);
                break;    

            case 'manufacture_jobcard_product_id':

                $this->manufacture_jobcard_product_id = $value;
                self::updatedManufactureJobcardProductId($value);

                break; //Moved to Lines 2024-03-15

            case 'extra_manufacture_jobcard_product_id':

                $this->manufacture_jobcard_product_id = $value;
                self::updatedExtraManufactureJobcardProductId($value);

                break;    
        }
    }

    function mount($dispatch, $dispatchaction)
    {
        $this->dispatch = $dispatch;
        $this->dispatch_adjust_qty = $this->dispatch->qty;
        $this->dispatch_return_weight_in = $this->dispatch->weight_out;
        $this->dispatch_transfer_weight_in = $this->dispatch->weight_out;
        $this->weight_out_datetime = date("Y-m-d\TH:i");
        $this->weight_out = $this->dispatch->weight_out;
        $this->over_under_variance = [];
        $this->manufacture_jobcard_product_id = $this->dispatch->manufacture_jobcard_product_id;
        $this->product_id = $this->dispatch->product_id;
        $this->dispatch_temp = $this->dispatch->dispatch_temp;
        $this->delivery_zone = $this->dispatch->delivery_zone;
        $this->qty = number_format($this->dispatch->qty, 3);
        $this->reference = $this->dispatch->reference;
        $this->job_id = $this->dispatch->job_id;
        $this->customer_id = $this->dispatch->customer_id;
        if($this->dispatch->manufacture_jobcard_product_id > 0){
            $jobcard = ManufactureJobcardProducts::where('id', $this->dispatch->manufacture_jobcard_product_id)->first();            
            $this->qty_due = number_format($jobcard->qty_due, 3);
        } else {
            $this->qty_due = number_format(0, 3);
        }

        $this->changingReferenceNo = 'false';        

        if ($dispatch->customer_id == '0') {
            $this->customer_dispatch = 0;
        } else {
            $this->customer_dispatch = 1;
        }


        //for returns
        $this->weight_in_datetime = date("Y-m-d\TH:i");
        $this->weight_in = 0;
        $this->weighedlist = '1';
        $this->dispatchaction = $dispatchaction;
        $this->add_extra_item_show = false;
        $this->extra_product_id = '0';
        $this->extra_product_qty = 0;
        $this->extra_item_error = false;
        $this->extra_item_message = '';
        $this->return_item_success = false;
        $this->return_item_message = '';
        $this->return_extraitem_success = false;
        $this->return_extraitem_message = '';
        $this->transfer_item_success = false;
        $this->transfer_item_message = '';
        $this->transfer_extraitem_success = false;
        $this->transfer_extraitem_message = '';
        $this->transfer_job_id = '0';
    }

    function updatedJobId($value)
    {
        if ($value > 0 && $this->dispatch->status == 'Loading') {
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'job_id' => $value,
                'customer_id' => 0,
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);

            $linked_transactions = ManufactureProductTransactions::where('dispatch_id', $this->dispatch->id)->get();
            //Remove any transactions that may be loaded already
            if(count($linked_transactions)>0){
                foreach ($linked_transactions as $transaction => $transaction_value){                    
                    if($transaction_value->manufacture_jobcard_product_id>0){
                        //Process remove by item to update jobs
                        Self::removeExtraItem($transaction_value->id);
                    } else {
                        //Delete Extra Item
                        ManufactureProductTransactions::where('id', $transaction_value->id)->delete();
                    }
                    
                }

            }
            

            $this->jobcard = ManufactureJobcards::where('id', $value)->first();
            $this->delivery = $this->jobcard->delivery;
            // Manufact
        }
    }

    function updatedCustomerId($value)
    {
        if ($value > 0 && $this->dispatch->status == 'Loading') {
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'job_id' => 0,
                'customer_id' => $value,
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);

            $linked_transactions = ManufactureProductTransactions::where('dispatch_id', $this->dispatch->id)->get();
            //Remove any transactions that may be loaded already
            if(count($linked_transactions)>0){
                foreach ($linked_transactions as $transaction => $transaction_value){                    
                    if($transaction_value->manufacture_jobcard_product_id>0){
                        //Process remove by item to update jobs
                        Self::removeExtraItem($transaction_value->id);
                    } else {
                        //Delete Extra Item
                        ManufactureProductTransactions::where('id', $transaction_value->id)->delete();
                    }                    
                }

            }
        }
    }

    function updatedReference($value)
    {
        if($this->dispatch->status == 'Loading'){
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'reference' => $value
            ]);  
        }
        
    }

    function updatedDeliveryZone($value)
    {
        if($this->dispatch->status == 'Loading'){
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'delivery_zone' => $value
            ]);    
        }
        
    }


    function updatedExtraProductQty()
    {
        $this->extra_item_error = false;       
    }

    function updatingWeightOut($value)
    {
        if($this->dispatch->status == 'Loading'){
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'weight_out' => 0,
                'qty' => 0
            ]);

            if($this->dispatch->jobcard_id > 0){
                $this->qty_due = number_format($this->dispatch->jobcard_product()->qty_due, 3);
            }    
        }
    }

    function updatedWeightOut($value)
    {
        
        if ($value < $this->dispatch->weight_in || $this->dispatch->status != 'Loading') return;        

        if($this->dispatch->jobcard_id > 0){
            $this->validate(['weight_out' => 'gt:0|lte:'.$this->qty_due + 0.5 + $this->dispatch->weight_in]);
        } else {            
            $this->validate(['weight_out' => 'gt:0|gt:'.$this->dispatch->weight_in]);
        }       

        //check if there are any weighed lines already
        $existing_weighed = ManufactureProductTransactions::from('manufacture_product_transactions as transactions')        
        ->join('manufacture_products as products', 'products.id', '=', 'transactions.product_id', 'left outer')
        ->select('transactions.id as id', 'transactions.dispatch_id as dispatch_id', 'transactions.product_id as product_id'
        , 'transactions.qty as qty', 'transactions.manufacture_jobcard_product_id as manufacture_jobcard_product_id', 'products.weighed_product as weighed_product')
        ->where('dispatch_id', $this->dispatch->id)
        ->where('weighed_product', '1')
        ->first();

        if(null !== $existing_weighed && strlen($existing_weighed)>0){
            //Check new Value and update to Max Allowed
            $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $existing_weighed->manufacture_jobcard_product_id)->first();
            if ($manufacture_jobcard_product) {                
                //Apply Variance of 500kg/ton on weighed items            
                $product_qty = $manufacture_jobcard_product->qty_due - $existing_weighed->qty;
                $proposed_new_qty = number_format(floatval($value) - floatval($this->dispatch->weight_in), 3);
                           
                if (($proposed_new_qty > $product_qty + 0.5 && $manufacture_jobcard_product->product()->weighed_product > 0)||($proposed_new_qty > $product_qty && $manufacture_jobcard_product->product()->weighed_product == 0)) {                    
                    $this->extra_item_error = true;
                    $this->extra_item_message = "Qty is not allowed to be more than Qty allocated on Job for this Product (plus Variance for weighed products). Qty will be capped at {$product_qty}. ";
                    $this->qty_due = number_format(floatval($product_qty), 3);
                    ManufactureProductTransactions::where('id', $existing_weighed->id)->update(['qty' => number_format(Functions::negate($product_qty), 3),
                    'weight_out' => number_format($value, 3)]);
                } else {
                    $this->extra_item_error = false;
                    $this->extra_item_message = "";
                    $this->qty_due = number_format(floatval($proposed_new_qty), 3);
                    ManufactureProductTransactions::where('id', $existing_weighed->id)->update(['qty' => number_format(Functions::negate($proposed_new_qty), 3),
                    'weight_out' => number_format($value, 3)]);                    
                }                
            }
        }

        $this->qty = number_format(floatval($value) - $this->dispatch->weight_in, 3);                
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'weight_out' => $value,
            'qty' => $this->qty
        ]);

        // if($this->qty_due){}
        
        /* if($this->dispatch->jobcard_id > 0){
            if (($this->dispatch->jobcard_product()->qty_due == 0 && $this->dispatch->jobcard_product()->product()->weighed_product == 0)||($this->dispatch->jobcard_product()->qty_due <= 0.5 && $this->dispatch->jobcard_product()->product()->weighed_product > 0)) {    
                ManufactureJobcardProducts::where('id', $this->dispatch->jobcard_product()->id)->update(['filled' => 1]);            
                if ($this->dispatch->jobcard_product()->qty_due <= 0.5 && $this->dispatch->jobcard_product()->qty_due >= -0.5 && $this->dispatch->jobcard_product()->qty_due != 0){$this->over_under_variance='Product filled with Variance of '.number_format(Functions::negate($this->dispatch->jobcard_product()->qty_due), 3).'.';}else{$this->over_under_variance='';}
            } else {
                ManufactureJobcardProducts::where('id', $this->dispatch->jobcard_product()->id)->update(['filled' => 0]);
                $this->over_under_variance='';
            }
        } */
        
    }

    function updatedDispatchAdjustQty($value)
    {
        if ($value > $this->dispatch->qty || $value < 0) {
            $this->dispatch_adjust_qty = $this->dispatch->qty;
        }
    }

    function updatedDispatchTemp($value)
    {
        if ($value < 0 || $this->dispatch->status != 'Loading') return;
        $this->dispatch_temp = $value;
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'dispatch_temp' => $value
        ]);
    }


    function updatedCustomerDispatch($value)
    {
        if ($this->dispatch->status == 'Loading'){
            $this->manufacture_jobcard_product_id = 0;
            $this->customer_id = 0;
            $this->job_id = 0;

            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'customer_id' => 0,
                'job_id' => 0,
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);    
        }        
    }

    function updatedExtraProductId($extra_product_id)
    {        
        if($this->dispatch->status == 'Loading'){
            $this->extraproduct = ManufactureProducts::where('id', $extra_product_id)->first();
            $this->extra_product_weighed = $this->extraproduct->weighed_product;
            
            $this->extra_product_unit_measure = $this->extraproduct->unit_measure;
            $this->extra_product_weight_in_date = $this->dispatch->weight_in_datetime;

            $this->manufacture_jobcard_product_id = 0;
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);    
        }
        
    }

    function updatedExtraManufactureJobcardProductId($value)
    {        
        if($this->dispatch->status == 'Loading'){
            $jobcard = ManufactureJobcardProducts::where('id', $value)->first();
            $this->extraproduct = ManufactureProducts::where('id', $jobcard->product_id)->first();
            $this->extra_product_weighed = $this->extraproduct->weighed_product;
            
            $this->extra_product_id = $jobcard->product_id;
            $this->extra_product_unit_measure = $this->extraproduct->unit_measure;
            $this->extra_product_weight_in_date = $this->dispatch->weight_in_datetime;

            if ($value > 0) {
                $this->product_id = $jobcard->product_id;
                $this->qty_due = number_format($jobcard->qty_due, 3);            
            } 
            
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);    
        }       
        
    }

    function updatedProductId($value)
    {
        /* $this->manufacture_jobcard_product_id = 0;
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'manufacture_jobcard_product_id' => 0,
            'product_id' => $value
        ]); */ //Moved to Lines 2024-03-14
    }

    function updatedManufactureJobcardProductId($value)
    {
        /* if ($value > 0) {
            $jobcard = ManufactureJobcardProducts::where('id', $value)->first();

            $this->product_id = $jobcard->product_id;

            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'manufacture_jobcard_product_id' => $value,
                'product_id' => $this->product_id
            ]);

            $this->qty_due = number_format($jobcard->qty_due, 3);
            
        } else {
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);
        } */ //Moved to Lines 2024-03-14
    }

    function AddExtraItemShow()
    {        
        if($this->add_extra_item_show == false){
            // Clear the Inputs
            $this->extra_product_id = '';
            $this->extra_product_unit_measure = '';
            $this->extra_product_qty = 0;
            $this->extra_product_weight_in_date = '';
            //Set error to blank
            $this->manufacture_jobcard_product_id = 0;
            $this->extra_item_message = '';
        }               
    }

    function removeExtraItem($extra_item_id)
    {
        if($this->dispatch->status == 'Loading'){
            $extra_item = ManufactureProductTransactions::where('id', $extra_item_id)->first();
            $manufacture_jobcard_product_id = $extra_item->manufacture_jobcard_product_id;

            //Delete Extra Item
            ManufactureProductTransactions::where('id', $extra_item_id)->delete();

            $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $manufacture_jobcard_product_id)->first();
            if ($manufacture_jobcard_product) {

                $product_qty = $manufacture_jobcard_product->qty_due;
                //Apply Variance of 500kg/ton on weighed items
                // if ($product_qty > 0) { 2024-02-28 Variances
                if (($product_qty > 0.5 && $manufacture_jobcard_product->product()->weighed_product > 0)||($product_qty > 0 && $manufacture_jobcard_product->product()->weighed_product == 0)) {
                    
                    ManufactureJobcardProducts::where('id', $manufacture_jobcard_product_id)->update(['filled' => 0]);
                                    
                    //Set job card as Open if filled <> 1
                    if (ManufactureJobcardProducts::where('job_id', $manufacture_jobcard_product->jobcard()->id)->where('filled', '0')->count() > 0) {
                        unset($this->over_under_variance[$manufacture_jobcard_product_id]);
                        ManufactureJobcards::where('id', $manufacture_jobcard_product->jobcard()->id)->update(['status' => 'Open']);
                    }
                    
                }
            }
        }
        
    }

    public function messages()
    {
        // dd($this->dispatch->product()->description);
        return [
            'dispatch_return_weight_in.lte' => 'The Return Weight In is more than Dispatch Weight Out.',
            'dispatch_return_weight_in.gt' => 'The Return Weight In is less than Dispatch Weight In.',
            'weight_out.gte' => 'The dispatched Qty cannot be less than 0',
            'weight_out.lte' => 'The dispatched Qty cannot be more than Unfilled Qty (plus Variance for weighed Products)',            
            'dispatch.qty.gte' => 'The Return / Transfer Qty is more than the Dispatched Qty.',
            'extraitemqty.gte' => 'The Return / Transfer Qty is more than the Dispatched Qty.',
            'transfer_job_id.required' => 'A New Jobcard is required.',
            'transfer_job_id.not_in' => 'The New Jobcard cannot be same as Old Jobcard.',
            'transfer_job_id.in' => 'The New Jobcard should contain Product ' . $this->dispatch->customer_id == '0' ? isset($this->dispatch->product()->description) : isset($this->dispatch->customer_product()->description),
            'return_extraitem_qty.gte' => 'The Return / Transfer Qty is more than the Dispatched Qty.',
        ];
    }

    function changeReferenceNo ($action)
    {
        if($action=='commit'){
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'reference' => $this->reference
            ]);
            $this->dispatch->reference=$this->reference;
        }

        $this->changingReferenceNo = 'false';        
    }

    function startReturnItem($id)
    {
        $this->dispatchaction = 'returning';
        $this->return_item_success = false;
    }

    function cancelReturnItem($id)
    {
        $this->dispatchaction = 'view';
        $this->dispatch_adjust_qty = $this->dispatch->qty;
    }

    function confirmReturnItem($id)
    {
        //NO LONGER IN USE - MOVED TO LINES
        //Transfer & Return Notes changes 2024-03-12
        $dispatch = ManufactureJobcardProductDispatches::where('id', $id)->first();

        $error = false;

        if ($dispatch->weight_out > 0) {
            $returnqty = $this->dispatch_return_weight_in - $dispatch->weight_in;
            $this->dispatch_adjust_qty = $returnqty;
        } else {
            $returnqty = $this->dispatch_adjust_qty;
        }

        $this->validate([
            'dispatch_return_weight_in' => 'lte:dispatch.weight_out|gt:dispatch.weight_in',
            'weight_out_datetime' => 'date'
        ]);

        //Compare what was dispatched with what is being returned
        $product_qty = $dispatch->qty;

        $this->validate([
            'dispatch.qty' => 'gte:dispatch_adjust_qty',
        ]);

        $newqty = $product_qty - $returnqty;

        if (!$error) {
            /* $form_fields = [
                'qty' => $newqty
            ];

            if ($newqty > 0) {
                $form_fields['status'] = 'Partial Returned';
            } elseif ($newqty == 0) {
                $form_fields['status'] = 'Returned';
            }

            ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields); */ //Old routine just adjusted existing value
            //Insert new line into Transactions for Return Transaction


            //If Jobcard Dispatch

            if ($dispatch->customer_id == '0') {
                //If Qty due after Dispatch Return is > 0 then set Product unfilled again
                //Apply Variance of 500kg/ton on weighed items
                //if ($dispatch->jobcard_product()->qty_due > 0) { 2024-02-28 Variances
                if (($dispatch->jobcard_product()->qty_due > 0.5 && $dispatch->jobcard_product()->product()->weighed_product > 0)||($dispatch->jobcard_product()->qty_due > 0 && $dispatch->jobcard_product()->product()->weighed_product == 0)) {    
                    ManufactureJobcardProducts::where('id', $dispatch->jobcard_product()->id)->update(['filled' => 0]);                    
                }

                //Set job card as Open if filled <> 1
                if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {

                    ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Open']);
                }

                //Returned Raw Product Transaction
                if ($dispatch->jobcard_product()->product()->has_recipe == 0) {
                    //Adjust transaction if no recipe
                    $form_fields = [
                        'product_id' => $dispatch->jobcard_product()->product()->id,
                        'type_id' => $dispatch->id,
                        // 'qty' => $returnqty,                        
                        'user_id' => auth()->user()->user_id,
                        'weight_out_user' => auth()->user()->user_id,
                        'weight_out_datetime' => date("Y-m-d\TH:i:s"),
                        'status' => 'Completed',                        
                    ];

                    if (isset($dispatch->plant()->reg_number)) {
                        $form_fields['registration_number'] = $dispatch->plant()->reg_number;
                    } else {
                        $form_fields['registration_number'] = $dispatch->registration_number;
                    }

                    //Jobcard Raw Material Return
                    $form_fields['comment'] = 'Returned for ' . $dispatch->jobcard()->jobcard_number;
                    $form_fields['type'] = 'JRETRN';

                    ManufactureProductTransactions::insert($form_fields);
                }
            } else {
                //Returned Raw Product Transaction
                if ($dispatch->customer_product()->has_recipe == 0) {
                    //Adjust transaction if no recipe
                    $form_fields = [
                        'product_id' => $dispatch->customer_product()->id,
                        'type_id' => $dispatch->id,
                        // 'qty' => $returnqty,                        
                        'user_id' => auth()->user()->user_id,
                        'weight_out_user' => auth()->user()->user_id,
                        'weight_out_datetime' => date("Y-m-d\TH:i:s"),
                        'status' => 'Completed',                        
                    ];

                    if (isset($dispatch->plant()->reg_number)) {
                        $form_fields['registration_number'] = $dispatch->plant()->reg_number;
                    } else {
                        $form_fields['registration_number'] = $dispatch->registration_number;
                    }

                    //Customer Raw Material Return
                    $form_fields['comment'] = 'Returned for ' . $dispatch->customer()->name;
                    $form_fields['type'] = 'CRETRN';

                    ManufactureProductTransactions::insert($form_fields);
                }
            }

            $this->dispatchaction = 'view';


            $this->return_item_success = true;

            if ($dispatch->customer_id == '0') {
                $this->return_item_message = "Dispatch No. " . $dispatch->dispatch_number . " has been returned. Job " . $dispatch->jobcard()->jobcard_number . " has been credited with " . $returnqty . " " . $dispatch->jobcard_product()->product()->description;
                $this->dispatch = ManufactureJobcardProductDispatches::where('id', $id)->first();

                return;
            } else {
                $this->return_item_message = "Dispatch No. " . $dispatch->dispatch_number . " has been returned. " . $returnqty . " " . $dispatch->customer_product()->description . " has been credited.";
                $this->dispatch = ManufactureJobcardProductDispatches::where('id', $id)->first();

                return;
            }
        }
    }

    function returnExtraItem($extra_item_id, $adjust_qty)
    {       
        //Transfer & Return Notes changes 2024-03-12
        $extra_item = ManufactureProductTransactions::where('id', $extra_item_id)->first();
        $manufacture_jobcard_product_id = $extra_item->manufacture_jobcard_product_id;
        $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $manufacture_jobcard_product_id)->first();

        $dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();        

        $error = false;

        $returnqty = $adjust_qty;

        //Compare what was dispatched with what is being returned
        $product_qty = $extra_item->qty;

        $newqty = $product_qty + $returnqty;  
        
        // dd($newqty);
        
        if (!$error) {
            // dd('validated');
            // $form_fields = [
            //     'qty' => $newqty
            // ]; Qty does not get adjusted anymore 2024-03-18

            // if ($newqty > 0) {
            //     $form_fields['status'] = 'Partial Returned';
            // } elseif ($newqty == 0) {
            //     $form_fields['status'] = 'Returned';
            // } Status on Return Transaction Line 2024-03-18


            // ManufactureProductTransactions::where('id', $extra_item->id)->update($form_fields); No Need to Update Exisiting Line anymore 2024-03-18

            //If Jobcard Dispatch
            if ($dispatch->customer_id == '0') {
                
                //Returned Raw Product Transaction
                // if ($manufacture_jobcard_product->product()->has_recipe == 0) { Old Return only entered for recipe items
                // if ($manufacture_jobcard_product->product()->has_recipe == 0 || $manufacture_jobcard_product->product()->weighed_product == 1) {
                //     //Adjust transaction if no recipe or weighed 2024-03-26 Removed to allow return on all items
                    $form_fields = [
                        'product_id' => $manufacture_jobcard_product->product()->id,
                        'manufacture_jobcard_product_id' => $manufacture_jobcard_product->id,
                        'dispatch_id' => $dispatch->id,
                        'type_id' => $dispatch->id,
                        'qty' => $returnqty,                        
                        'user_id' => auth()->user()->user_id
                        // 'status' => ' '
                    ];

                    if ($newqty < 0) {
                        $form_fields['status'] = 'Partial Return';
                    } elseif ($newqty == 0) {
                        $form_fields['status'] = 'Returned';
                    }

                    if (isset($dispatch->plant()->reg_number)) {
                        $form_fields['registration_number'] = $dispatch->plant()->reg_number;
                    } else {
                        $form_fields['registration_number'] = $dispatch->registration_number;
                    }

                    //Jobcard Raw Material Return
                    $form_fields['comment'] = 'Returned for ' . $dispatch->jobcard()->jobcard_number;
                    $form_fields['type'] = 'JRETRN';

                    //Set New Dispatch & Transactions
                    $new_dispatch_number=Functions::get_doc_number('dispatch'); 
                    $new_dispatch_form_fields=$this->dispatch->toArray();
                    unset($new_dispatch_form_fields['id']);
                    unset($new_dispatch_form_fields['qty']);
                    unset($new_dispatch_form_fields['created_at']);
                    unset($new_dispatch_form_fields['updated_at']);
                    $new_dispatch_form_fields['dispatch_number']=$new_dispatch_number;
                    $new_dispatch_form_fields['weight_in']=0;
                    $new_dispatch_form_fields['weight_in_datetime']=date("Y-m-d\TH:i");
                    $new_dispatch_form_fields['weight_in_user_id']=Auth::user()->user_id;
                    $new_dispatch_form_fields['weight_out']=0;
                    $new_dispatch_form_fields['weight_out_datetime']=date("Y-m-d\TH:i");
                    $new_dispatch_form_fields['weight_out_user_id']=Auth::user()->user_id;                    
                    $new_dispatch_form_fields['status']='Returned';
                    $new_dispatch_id=ManufactureJobcardProductDispatches::insertGetId($new_dispatch_form_fields);

                    $new_transaction_form_fields=$form_fields;
                    $new_transaction_form_fields['dispatch_id']=$new_dispatch_id;
                    $new_transaction_form_fields['type_id']=$new_dispatch_id;                    
                    $new_transaction_form_fields['user_id']=Auth::user()->user_id;
                    $new_transaction_form_fields['weight_out_user'] = auth()->user()->user_id;
                    $new_transaction_form_fields['weight_out_datetime'] = date("Y-m-d\TH:i:s");
                    $new_transaction_form_fields['status'] = 'Returned';
                    
                    $new_return_line_id = ManufactureProductTransactions::insertGetId($new_transaction_form_fields);

                    // $new_return_line_id = ManufactureProductTransactions::insertGetId($form_fields);                    

                    //If Qty due after Dispatch Return is > 0 then set Product unfilled again
                    //Apply Variance of 500kg/ton on weighed items
                    //if ($manufacture_jobcard_product->qty_due > 0) { 2024-02-28 Variances                      
                    if (($manufacture_jobcard_product->qty_due > 0.5 && $manufacture_jobcard_product->product()->weighed_product > 0)||($manufacture_jobcard_product->qty_due > 0 && $manufacture_jobcard_product->product()->weighed_product == 0)) {   
                    
                        ManufactureJobcardProducts::where('id', $manufacture_jobcard_product->id)->update(['filled' => 0]);
                    }
                    //Set job card as Open if filled <> 1
                    if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {
                        
                        ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Open']);
                    }
                // }
            } else {
                //Returned Raw Product Transaction
                // if ($extra_item->customer_product()->has_recipe == 0) { Old Return only entered for recipe items
                // if ($extra_item->customer_product()->has_recipe == 0 || $extra_item->customer_product()->weighed_product == 1) {
                //     //Adjust transaction if no recipe 2024-03-26 Removed to allow return on all items
                    $form_fields = [
                        'product_id' => $extra_item->customer_product()->id,
                        'dispatch_id' => $dispatch->id,
                        'type_id' => $dispatch->id,
                        'qty' => $returnqty,                        
                        'user_id' => auth()->user()->user_id
                        // 'status' => ' '
                    ];

                    if ($newqty < 0) {
                        $form_fields['status'] = 'Partial Return';
                    } elseif ($newqty == 0) {
                        $form_fields['status'] = 'Returned';
                    }

                    if (isset($dispatch->plant()->reg_number)) {
                        $form_fields['registration_number'] = $dispatch->plant()->reg_number;
                    } else {
                        $form_fields['registration_number'] = $dispatch->registration_number;
                    }

                    //Customer Raw Material Return
                    $form_fields['comment'] = 'Returned for ' . $dispatch->customer()->name;
                    $form_fields['type'] = 'CRETRN';

                    //Set New Dispatch & Transactions
                    $new_dispatch_number=Functions::get_doc_number('dispatch'); 
                    $new_dispatch_form_fields=$this->dispatch->toArray();
                    unset($new_dispatch_form_fields['id']);
                    unset($new_dispatch_form_fields['qty']);
                    unset($new_dispatch_form_fields['created_at']);
                    unset($new_dispatch_form_fields['updated_at']);
                    $new_dispatch_form_fields['dispatch_number']=$new_dispatch_number;
                    $new_dispatch_form_fields['weight_in']=0;
                    $new_dispatch_form_fields['weight_in_datetime']=date("Y-m-d\TH:i");
                    $new_dispatch_form_fields['weight_in_user_id']=Auth::user()->user_id;
                    $new_dispatch_form_fields['weight_out']=0;
                    $new_dispatch_form_fields['weight_out_datetime']=date("Y-m-d\TH:i");
                    $new_dispatch_form_fields['weight_out_user_id']=Auth::user()->user_id;                    
                    $new_dispatch_form_fields['status']='Returned';
                    $new_dispatch_id=ManufactureJobcardProductDispatches::insertGetId($new_dispatch_form_fields);

                    $new_transaction_form_fields=$form_fields;
                    $new_transaction_form_fields['dispatch_id']=$new_dispatch_id;
                    $new_transaction_form_fields['type_id']=$new_dispatch_id;                    
                    $new_transaction_form_fields['user_id']=Auth::user()->user_id;
                    $new_transaction_form_fields['weight_out_user'] = auth()->user()->user_id;
                    $new_transaction_form_fields['weight_out_datetime'] = date("Y-m-d\TH:i:s");
                    $new_transaction_form_fields['status'] = 'Returned';
                    
                    $new_return_line_id = ManufactureProductTransactions::insertGetId($new_transaction_form_fields);
                    
                    // $new_return_line_id = ManufactureProductTransactions::insertGetId($form_fields);
                // }
            }

            $this->dispatchaction = 'view';            

            if($new_return_line_id > 0){$this->return_extraitem_success = true;} else {$this->return_extraitem_success = false;}

            if ($dispatch->customer_id == '0') {
                $this->return_extraitem_message = "A Return has been booked on Return Dispatch No. " . $new_dispatch_number . ". Job " . $dispatch->jobcard()->jobcard_number . " has been credited with " . $returnqty . " " . $manufacture_jobcard_product->product()->description;
                $this->dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();
                //***print return note
                if($new_return_line_id > 0){
                    //Spool Return Printout Document                    
                    // dd('new line');
                    return back()->with(['print_return' => $new_return_line_id,
                    'print_return_dispatch_id' => $new_dispatch_id,
                    'print_return_extraitem_id' => $new_return_line_id]); 
                } else {
                    //There was an error, no printout just return
                    // dd('new line error');
                    return;
                }                
            } else {
                $this->return_extraitem_message = "A Return has been booked on Return Dispatch No. " . $new_dispatch_number . ". " . $returnqty . " " . $extra_item->customer_product()->description . " has been credited.";
                $this->dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();
                //***print return note
                if($new_return_line_id > 0){
                    //Spool Return Printout Document
                    // dd('new line');
                    return back()->with(['print_return' => $new_return_line_id,
                    'print_return_dispatch_id' => $new_dispatch_id,
                    'print_return_extraitem_id' => $new_return_line_id]);                    
                } else {
                    //There was an error, no printout just return
                    // dd('new line error');
                    return;
                }                
            }
        }
    }

    function startTransferItem($id)
    {
        $this->dispatchaction = 'transfering';
        $this->transfer_item_success = false;
    }

    function cancelTransferItem($id)
    {
        $this->dispatchaction = 'view';
        $this->dispatch_adjust_qty = $this->dispatch->qty;
    }

    function refreshNotify(){

        $this->emit('refreshNotifications');

    } 

    function transferExtraItemRequest($extra_item_id, $adjust_qty, $new_customer_id, $new_job_id, $new_job_product_id)
    {   
        
        //Build Approval Request.
        $approval_request = ['request_type'=>'Dispatch Transfer',
            'request_model'=>'manufacture_product_transactions',
	        'request_model_id'=>$extra_item_id,
	        'requesting_user_id'=>Auth::user()->user_id,
	        'approving_user_id'=>'',	
            'request_detail_array'=> base64_encode(json_encode(['adjust_qty'=>$adjust_qty,
            'transfer_job_id'=>$new_job_id,
            'transfer_customer_id'=>$new_customer_id,
            'transfer_job_product_id'=>$new_job_product_id,]))  
        ]; 
        
        // dd($approval_request);
        //Update Extra Item Status to Transfer Requested
        $form_fields=['status'=>'Transfer Requested'];
        ManufactureProductTransactions::whereId($extra_item_id)->update($form_fields);

        //Insert Approval Request
        Approvals::insert($approval_request);
        // $this->emit('refreshNotifications');

        //Get Approval Users
        $approval_users = User::whereIn('user_id', array(DB::raw('select user_id from user_sec_tbl where dispatch_transfer_approve=1')))->whereActive('1')->get()->toArray();
        
        foreach($approval_users as $user){
            //SMS Transfer request Notification.
            if($user['contact_number'] != '') Functions::sms_($user['contact_number'], '['.date("Y-m-d\TH:i").'] Transfer Requested on Dispatch '.$this->dispatch->dispatch_number.' by '.Auth::user()->name.' '.Auth::user()->last_name.'. Please review at your earliest convenience', '', '');
            
            if($user['email'] != '')Functions::intmail_($user['email'], 
            date("Y-m-d\TH:i").': Transfer Requested on Dispatch '.$this->dispatch->dispatch_number.' by '.Auth::user()->name.' '.Auth::user()->last_name.'. Please review at your earliest convenience by clicking the link below.',
            env('MAIL_FROM_ADDRESS', Auth::user()->email), 
            'Dispatch Transfer Request - Dispatch No '.$this->dispatch->dispatch_number,
            /* temp removed */['link'=>['url'=>env('APP_URL','').'/dispatches/new',
            'description'=>'Review Transfer Request on '.$this->dispatch->dispatch_number]] );
        }

        $this->transfer_extraitem_message = "Transfer Requested.";

    }

    function transferExtraItemConfirm($extra_item_id, $adjust_qty, $new_customer_id, $new_job_id, $new_job_product_id)
    {              
        //Transfer & Return Notes changes 2024-03-12        
        $extra_item = ManufactureProductTransactions::where('id', $extra_item_id)->first();
        
        //Check that Transfer is still Requested
        if($extra_item->status=='Transfer Requested'){
            $new_manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $new_job_product_id)->first();        

            $manufacture_jobcard_product_id = $extra_item->manufacture_jobcard_product_id;
            $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $manufacture_jobcard_product_id)->first();

            $dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();

            $error = false;

            $transferqty = $adjust_qty;

            //Compare what was dispatched with what is being returned
            $product_qty = $extra_item->qty;

            $newqty = $product_qty + $transferqty;  
            
            // dd($newqty);
            
            if (!$error) {           

                //If Jobcard Dispatch
                if ($dispatch->customer_id == '0') {
                    
                    //Returned Raw Product Transaction
                    // if ($manufacture_jobcard_product->product()->has_recipe == 0) { Old Return only entered for recipe items
                    // if ($manufacture_jobcard_product->product()->has_recipe == 0 || $manufacture_jobcard_product->product()->weighed_product == 1) {
                    //     //Adjust transaction if no recipe or weighed on Old Dispatch 2024-03-26 Removed to allow transfer on all items
                        $form_fields = [
                            'product_id' => $manufacture_jobcard_product->product()->id,
                            'manufacture_jobcard_product_id' => $manufacture_jobcard_product->id,
                            'dispatch_id' => $dispatch->id,
                            'type_id' => $dispatch->id,
                            'qty' => number_format($transferqty, 3),                        
                            'user_id' => auth()->user()->user_id,
                            'weight_out_user' => auth()->user()->user_id,
                            'weight_out_datetime' => date("Y-m-d\TH:i:s"),                            
                            // 'status' => ' '
                        ];

                        if ($newqty < 0) {
                            $form_fields['status'] = 'Partial Transfer';
                        } elseif ($newqty == 0) {
                            $form_fields['status'] = 'Transferred';
                        }

                        if (isset($dispatch->plant()->reg_number)) {
                            $form_fields['registration_number'] = $dispatch->plant()->reg_number;
                        } else {
                            $form_fields['registration_number'] = $dispatch->registration_number;
                        }

                        //Jobcard Raw Material Return
                        $form_fields['comment'] = 'Transferred from ' . $dispatch->jobcard()->jobcard_number/*  . ' to ' . $new_dispatch->jobcard()->jobcard_number */;
                        $form_fields['type'] = 'JTRANSFR';

                        $new_transfer_line_id = ManufactureProductTransactions::insertGetId($form_fields);                                                           

                        //If Qty due after Dispatch Return is > 0 then set Product unfilled again
                        //Apply Variance of 500kg/ton on weighed items
                        //if ($manufacture_jobcard_product->qty_due > 0) { 2024-02-28 Variances                      
                        if (($manufacture_jobcard_product->qty_due > 0.5 && $manufacture_jobcard_product->product()->weighed_product > 0)||($manufacture_jobcard_product->qty_due > 0 && $manufacture_jobcard_product->product()->weighed_product == 0)) {   
                        
                            ManufactureJobcardProducts::where('id', $manufacture_jobcard_product->id)->update(['filled' => 0]);
                        }
                        //Set job card as Open if filled <> 1
                        if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {
                            
                            ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Open']);
                        }
                        
                        //Create New Dispatch with Lines
                        //Dispatch Header
                        $form_fields = [
                            'dispatch_number' => $dispatch->dispatch_number,
                            'reference' => ''/* $dispatch->reference not sure???*/,                        
                            'delivery_zone' => $dispatch->delivery_zone,
                            'dispatch_temp' => $dispatch->dispatch_temp,                        
                            'comment' => $dispatch->comment,
                            'use_historical_weight_in' => $dispatch->use_historical_weight_in,
                            'weight_in' => $dispatch->weight_in,
                            'weight_in_datetime' => $dispatch->weight_in_datetime,
                            'weight_in_user_id' => auth()->user()->user_id,
                            'weight_out' => $dispatch->weight_out,
                            'weight_out_datetime' => $dispatch->weight_out_datetime,
                            'weight_out_user_id' => auth()->user()->user_id,
                            'qty' => $dispatch->qty,
                            'status' => 'Dispatched',
                            'plant_id' => $dispatch->plant_id,
                            'outsourced_contractor' => $dispatch->outsourced_contractor,
                            'registration_number' => $dispatch->registration_number,
                            'customer_id' => $new_customer_id,
                            'job_id' => $new_job_id,
                            'product_id' => $dispatch->product_id,
                            'manufacture_jobcard_product_id' => $dispatch->manufacture_jobcard_product_id,
                        ];
                        if($new_manufacture_jobcard_product !== null){
                            $form_fields['delivery_address'] = $new_manufacture_jobcard_product->jobcard()->delivery_address;
                        } else {
                            $form_fields['delivery_address'] = ManufactureCustomers::where('id', $new_customer_id)->first()->address;
                        }
                        $dupe_check = ['dispatch_number'=>$dispatch->dispatch_number,
                            'customer_id'=>$new_customer_id,
                            'job_id'=>$new_job_id,
                        ];                    
                        $new_dispatch = ManufactureJobcardProductDispatches::updateOrCreate($dupe_check, $form_fields);                    
                        
                        //Dispatch Lines
                        $form_fields = [                        
                            'dispatch_id' => $new_dispatch->id,
                            'type_id' => $new_dispatch->id,
                            'qty' => number_format(Functions::negate($transferqty), 3),                        
                            'user_id' => auth()->user()->user_id,
                            'weight_out_user' => auth()->user()->user_id,
                            'weight_out_datetime' => date("Y-m-d\TH:i:s"),                            
                            'status' => 'Dispatched'
                        ];
                        if($new_manufacture_jobcard_product !== null){
                            $form_fields['product_id'] = $new_manufacture_jobcard_product->product()->id;                        
                            $form_fields['manufacture_jobcard_product_id'] = $new_manufacture_jobcard_product->id;
                        } else {                        
                            $form_fields['product_id'] = $extra_item->customer_product()->id;                        
                            $form_fields['manufacture_jobcard_product_id'] = '0';
                        }                    

                        if (isset($dispatch->plant()->reg_number)) {
                            $form_fields['registration_number'] = $new_dispatch->plant()->reg_number;
                        } else {
                            $form_fields['registration_number'] = $new_dispatch->registration_number;
                        }

                        //Jobcard Raw Material Return
                        if($new_dispatch->job_id > 0){
                            $form_fields['comment'] = 'Dispatched for ' . $new_dispatch->jobcard()->jobcard_number;
                            $form_fields['type'] = 'JDISP';
                        } else {
                            $form_fields['comment'] = 'Dispatched for ' . ManufactureCustomers::where('id', $new_customer_id)->first()->name;
                            $form_fields['type'] = 'CDISP';
                        }
                        

                        $new_dispatch_line_id = ManufactureProductTransactions::insertGetId($form_fields);                                        

                        if($new_manufacture_jobcard_product !== null){
                            //Apply Variance of 500kg/ton on weighed items
                            $product_qty = $new_manufacture_jobcard_product->qty_due;
                            //if ($product_qty == 0) { 2024-02-28 Variances
                            if (($product_qty <= 0.5 && $new_manufacture_jobcard_product->product()->weighed_product > 0)||($product_qty == 0 && $new_manufacture_jobcard_product->product()->weighed_product == 0)) {
                                ManufactureJobcardProducts::where('id', $new_manufacture_jobcard_product->id)->update(['filled' => 1]);
                            }
                            
                            //Set job card as Filled if filled <> 0
                            if (ManufactureJobcardProducts::where('job_id', $new_manufacture_jobcard_product->jobcard()->id)->where('filled', '0')->count() == 0) {

                                ManufactureJobcards::where('id', $new_manufacture_jobcard_product->jobcard()->id)->update(['status' => 'Filled']);
                            }
                        }

                    // }
                } else {
                    //Returned Raw Product Transaction
                    // if ($extra_item->customer_product()->has_recipe == 0) { Old Return only entered for recipe items
                    // if ($extra_item->customer_product()->has_recipe == 0 || $extra_item->customer_product()->weighed_product == 1) {
                    //     //Adjust transaction if no recipe 2024-03-26 Removed to allow transfer on all items
                        $form_fields = [
                            'product_id' => $extra_item->customer_product()->id,
                            'dispatch_id' => $dispatch->id,
                            'type_id' => $dispatch->id,
                            'qty' => $transferqty,                        
                            'user_id' => auth()->user()->user_id,                            
                            'weight_out_user' => auth()->user()->user_id,
                            'weight_out_datetime' => date("Y-m-d\TH:i:s"),
                            // 'status' => ' '
                        ];

                        if ($newqty < 0) {
                            $form_fields['status'] = 'Partial Transfer';
                        } elseif ($newqty == 0) {
                            $form_fields['status'] = 'Transferred';
                        }

                        if (isset($dispatch->plant()->reg_number)) {
                            $form_fields['registration_number'] = $dispatch->plant()->reg_number;
                        } else {
                            $form_fields['registration_number'] = $dispatch->registration_number;
                        }

                        //Customer Raw Material Return
                        $form_fields['comment'] = 'Transferred from ' . $dispatch->customer()->name/*  . ' to ' . $new_dispatch->customer()->name */;
                        $form_fields['type'] = 'CTRANSFR';


                        $new_transfer_line_id = ManufactureProductTransactions::insertGetId($form_fields);
                        $new_transfer_line = ManufactureProductTransactions::where('id', $new_transfer_line_id)->get();

                        //Create New Dispatch with Lines
                        //Dispatch Header
                        $form_fields = [
                            'dispatch_number' => $dispatch->dispatch_number,
                            'reference' => ''/* $dispatch->reference not sure???*/,                        
                            'delivery_zone' => $dispatch->delivery_zone,
                            'dispatch_temp' => $dispatch->dispatch_temp,                        
                            'comment' => $dispatch->comment,
                            'use_historical_weight_in' => $dispatch->use_historical_weight_in,
                            'weight_in' => $dispatch->weight_in,
                            'weight_in_datetime' => $dispatch->weight_in_datetime,
                            'weight_in_user_id' => auth()->user()->user_id,
                            'weight_out' => $dispatch->weight_out,
                            'weight_out_datetime' => $dispatch->weight_out_datetime,
                            'weight_out_user_id' => auth()->user()->user_id,
                            'qty' => $dispatch->qty,
                            'status' => 'Dispatched',
                            'plant_id' => $dispatch->plant_id,
                            'outsourced_contractor' => $dispatch->outsourced_contractor,
                            'registration_number' => $dispatch->registration_number,
                            'customer_id' => $new_customer_id,
                            'job_id' => $new_job_id,
                            'product_id' => $dispatch->product_id,
                            'manufacture_jobcard_product_id' => $dispatch->manufacture_jobcard_product_id,
                        ];
                        if($new_manufacture_jobcard_product !== null){
                            $form_fields['delivery_address'] = $new_manufacture_jobcard_product->jobcard()->delivery_address;
                        } else {
                            $form_fields['delivery_address'] = ManufactureCustomers::where('id', $new_customer_id)->first()->address;
                        }
                        $dupe_check = ['dispatch_number'=>$dispatch->dispatch_number,
                            'customer_id'=>$new_customer_id,
                            'job_id'=>$new_job_id,
                        ];                    
                        $new_dispatch = ManufactureJobcardProductDispatches::updateOrCreate($dupe_check, $form_fields);                    
                        
                        //Dispatch Lines
                        $form_fields = [                        
                            'dispatch_id' => $new_dispatch->id,
                            'type_id' => $new_dispatch->id,
                            'qty' => number_format(Functions::negate($transferqty), 3),                        
                            'user_id' => auth()->user()->user_id,
                            'weight_out_user' => auth()->user()->user_id,
                            'weight_out_datetime' => date("Y-m-d\TH:i:s"),
                            'status' => 'Dispatched'
                        ];
                        if($new_manufacture_jobcard_product !== null){
                            $form_fields['product_id'] = $new_manufacture_jobcard_product->product()->id;                        
                            $form_fields['manufacture_jobcard_product_id'] = $new_manufacture_jobcard_product->id;
                        } else {                        
                            $form_fields['product_id'] = $extra_item->customer_product()->id;                        
                            $form_fields['manufacture_jobcard_product_id'] = '0';
                        }                    

                        if (isset($dispatch->plant()->reg_number)) {
                            $form_fields['registration_number'] = $new_dispatch->plant()->reg_number;
                        } else {
                            $form_fields['registration_number'] = $new_dispatch->registration_number;
                        }

                        //Jobcard Raw Material Return
                        if($new_dispatch->job_id > 0){
                            $form_fields['comment'] = 'Dispatched for ' . $new_dispatch->jobcard()->jobcard_number;
                            $form_fields['type'] = 'JDISP';
                        } else {
                            $form_fields['comment'] = 'Dispatched for ' . ManufactureCustomers::where('id', $new_customer_id)->first()->name;
                            $form_fields['type'] = 'CDISP';
                        }
                        

                        $new_dispatch_line_id = ManufactureProductTransactions::insertGetId($form_fields);

                        if($new_manufacture_jobcard_product !== null){
                            //Apply Variance of 500kg/ton on weighed items
                            $product_qty = $new_manufacture_jobcard_product->qty_due;
                            //if ($product_qty == 0) { 2024-02-28 Variances
                            if (($product_qty <= 0.5 && $new_manufacture_jobcard_product->product()->weighed_product > 0)||($product_qty == 0 && $new_manufacture_jobcard_product->product()->weighed_product == 0)) {
                                ManufactureJobcardProducts::where('id', $new_manufacture_jobcard_product->id)->update(['filled' => 1]);
                            }
                            
                            //Set job card as Filled if filled <> 0
                            if (ManufactureJobcardProducts::where('job_id', $new_manufacture_jobcard_product->jobcard()->id)->where('filled', '0')->count() == 0) {

                                ManufactureJobcards::where('id', $new_manufacture_jobcard_product->jobcard()->id)->update(['status' => 'Filled']);
                            }
                        }
                    // }
                }

                $this->dispatchaction = 'view';
                
                if($new_transfer_line_id > 0){$this->transfer_extraitem_success = true;} else {$this->transfer_extraitem_success = false;}

                if ($dispatch->customer_id == '0') {
                    $this->transfer_extraitem_message = "Dispatch No. " . $dispatch->dispatch_number . " has been transferred. Job " . $dispatch->jobcard()->jobcard_number . " has been credited with " . $transferqty . " " . $manufacture_jobcard_product->product()->description;
                    $this->dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();
                    //***print transfer note
                    if($new_transfer_line_id > 0){
                        //Spool Return Printout Document                    
                        // dd('new line');
                        return back()->with(['print_transfer' => $new_transfer_line_id,
                        'print_transfer_dispatch_id' => $dispatch->id,
                        'print_transfer_extraitem_id' => $new_transfer_line_id]); 
                    } else {
                        //There was an error, no printout just return
                        // dd('new line error');
                        return;
                    }                
                } else {
                    $this->transfer_extraitem_message = "Dispatch No. " . $dispatch->dispatch_number . " has been transferred. " . $transferqty . " " . $extra_item->customer_product()->description . " has been credited.";
                    $this->dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();
                    //***print transfer note
                    if($new_transfer_line_id > 0){
                        //Spool Return Printout Document
                        // dd('new line');
                        return back()->with(['print_transfer' => $new_transfer_line_id,
                        'print_transfer_dispatch_id' => $dispatch->id,
                        'print_transfer_extraitem_id' => $new_transfer_line_id]);                    
                    } else {
                        //There was an error, no printout just return
                        // dd('new line error');
                        return;
                    }                
                }
            }

        }        

        
    }

    function confirmTransferItem($id)
    {
        //NO LONGER IN USE - MOVED TO LINES
        //Transfer & Return Notes changes 2024-03-12
        $dispatch = ManufactureJobcardProductDispatches::where('id', $id)->first();
        if ($dispatch->customer_id == '0') {
            $related_product_jobs = ManufactureJobcardProducts::select('job_id')->where('product_id', $dispatch->product()->id)->get()->toArray();
        } else {
            $related_product_jobs = ManufactureJobcardProducts::select('job_id')->where('product_id', $dispatch->customer_product()->id)->get()->toArray();
        }
        $related_product_jobs_list = '';
        foreach ($related_product_jobs as $related_product_job) {
            $related_product_jobs_list .= $related_product_job['job_id'] . ', ';
        }

        $this->validate([
            'transfer_job_id' => 'required|not_in:' . $dispatch->job_id . '|in:' . $related_product_jobs_list,
        ]);

        $error = false;

        if ($this->transfer_job_id !== null) {
            //Jobcard Transfer
            if ($dispatch->customer_id == '0') {
                $newjobcard = ManufactureJobcardProducts::where('job_id', $this->transfer_job_id)->where('filled', '0')->where('product_id', $dispatch->product()->id)->first();
            } else {
                $newjobcard = ManufactureJobcardProducts::where('job_id', $this->transfer_job_id)->where('filled', '0')->where('product_id', $dispatch->customer_product()->id)->first();
            }

            //Compare what was dispatched with what is being transferred
            //Apply Variance of 500kg/ton on weighed items
            // if ($newjobcard->qty_due <= $dispatch->qty) { 2024-02-28 Variances                
            if (($newjobcard->qty_due <= $dispatch->qty && $newjobcard->product()->weighed_product == 0) || ($newjobcard->qty_due <= $dispatch->qty-0.5 && $newjobcard->product()->weighed_product > 0)) {
                $filledqty = $newjobcard->qty_due;
            } else {
                $filledqty = $dispatch->qty;
            }
        } else {
            //Customer Transfer            
            $newcustomer = ManufactureCustomers::where('id', $dispatch->customer_id)->first();
            $filledqty = $dispatch->qty;
        }

        if (!$error) {
            $form_fields = [
                'qty' => 0,
                'status' => 'Transferred'
            ];

            //set this->dispatch qty to zero
            //credit this->jobcard with transfer qty
            ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

            if ($dispatch->customer_id == '0') {
                //set this->jobcard status if required after qty credit
                //Apply Variance of 500kg/ton on weighed items
                //if ($dispatch->jobcard_product()->qty_due > 0) { 2024-02-28 Variances
                if (($dispatch->jobcard_product()->qty_due > 0.5 && $dispatch->jobcard_product()->product()->weighed_product > 0)||($dispatch->jobcard_product()->qty_due > 0 && $dispatch->jobcard_product()->product()->weighed_product == 0)) {
                    ManufactureJobcardProducts::where('id', $dispatch->jobcard_product()->id)->update(['filled' => 0]);
                }

                if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {

                    ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Open']);
                }
            }

            //Add lines if any Header???


            //Clone this->dispatch. Change JC, Delivery Zone based on this->request details
            $form_fields = [
                "dispatch_number" => $dispatch->dispatch_number,
                "reference" => $dispatch->reference,
                "delivery_zone" => $dispatch->delivery_zone,
                "dispatch_temp" => $dispatch->dispatch_temp,
                "comment" => $dispatch->comment,
                "weight_in" => $dispatch->weight_in,
                "weight_in_user_id" => auth()->user()->user_id,
                "weight_in_datetime" => $dispatch->weight_in_datetime,
                "weight_out" => $dispatch->weight_out,
                "weight_out_user_id" => auth()->user()->user_id,
                "weight_out_datetime" => date("Y-m-d\TH:i"),
                "status" => 'Dispatched',
                "plant_id" => $dispatch->plant_id,
                "registration_number" => $dispatch->registration_number,
                "qty" => $filledqty,
            ];

            if ($this->transfer_job_id !== null) {
                //Jobcard Dispatch               
                $form_fields['manufacture_jobcard_product_id'] = $newjobcard->id;
                $form_fields['job_id'] = $this->transfer_job_id;
                $form_fields['product_id'] = $dispatch->product_id;
                $form_fields['customer_id'] = 0;
            } else {
                //Customer Dispatch
                if ($dispatch->customer_id == '0') {
                    $form_fields['product_id'] = $dispatch->jobcard_product()->product()->id;
                } else {
                    $form_fields['product_id'] = $dispatch->product_id;
                }
                $form_fields['customer_id'] = $dispatch->customer_id;
            }

            $newdispatch_id = ManufactureJobcardProductDispatches::insertGetId($form_fields);

            if ($this->transfer_job_id !== null) {
                //Adjust status on clone->dispatch->Jobcard if filled
                //Apply Variance of 500kg/ton on weighed items
                //if ($newjobcard->qty_due == 0) { 2028-02-28 Variances
                if (($newjobcard->qty_due == 0 && $newjobcard->product()->weighed_product == 0)||($newjobcard->qty_due <= 0.5 && $newjobcard->product()->weighed_product > 0)) {    
                    ManufactureJobcardProducts::where('id', $newjobcard->id)->update(['filled' => 1]);
                }

                if (ManufactureJobcardProducts::where('job_id', $this->transfer_job_id)->where('filled', '0')->count() == 0) {

                    ManufactureJobcards::where('id', $this->transfer_job_id)->update(['status' => 'Filled']);
                }
            }

            //Adjust Raw Product Transactions
            $form_fields = [];
            if ($dispatch->customer_id == '0') {
                if ($dispatch->jobcard_product()->product()->has_recipe == 0) {

                    //Jobcard Raw Material Return
                    if ($this->transfer_job_id !== null) {
                        $form_fields['comment'] = 'Dispatched on ' . $newjobcard->jobcard()->jobcard_number;
                        $form_fields['type'] = 'JDISP';
                    } else {
                        $form_fields['comment'] = 'Dispatched for ' . $newcustomer->name;
                        $form_fields['type'] = 'CDISP';
                    }
                    $form_fields['type_id'] = $newdispatch_id;

                    ManufactureProductTransactions::where('type_id', $dispatch->id)->update($form_fields);
                }
            } else {
                if ($dispatch->customer_product()->has_recipe == 0) {

                    //Jobcard Raw Material Return
                    if ($this->transfer_job_id !== null) {
                        $form_fields['comment'] = 'Dispatched on ' . $newjobcard->jobcard()->jobcard_number;
                        $form_fields['type'] = 'JDISP';
                    } else {
                        $form_fields['comment'] = 'Dispatched for ' . $newcustomer->name;
                        $form_fields['type'] = 'CDISP';
                    }

                    $form_fields['type_id'] = $newdispatch_id;


                    ManufactureProductTransactions::where('type_id', $dispatch->id)->update($form_fields);
                }
            }

            //Refresh Archive Tab
            $this->emit('refreshArchiveDispatch');
            //Close Modal & Print new Dispatch
            $this->emit('closeModal', $dispatch->id, $newdispatch_id);
        }
    }

    function AddExtraItem($dispatch_id)
    {
        //Transfer & Return Notes changes 2024-03-12
        $this->extra_item_error = false;
        $this->extra_item_message = '';

        $form_fields = [
            "dispatch_id" => $dispatch_id,
            "reference_number" => $this->reference,
            "weight_in" => $this->dispatch->weight_in,
            "registration_number" => ($this->dispatch->registration_number == null ? '' : $this->dispatch->registration_number),
            "status" => "Loading",
            "weight_out_user" => auth()->user()->user_id,
            "weight_out_datetime" => date("Y-m-d\TH:i"),
            "weight_in_user" => auth()->user()->user_id,
            "weight_in_datetime" => date("Y-m-d\TH:i"),
            "dispatch_temp" => $this->dispatch_temp,
            "user_id" => auth()->user()->user_id,            
        ];

        if($this->dispatch->customer_id > 0){
            $form_fields['type'] = 'CDISP';
            $form_fields['comment'] = 'Dispatched for ' . ManufactureCustomers::where('id', $this->dispatch->customer_id)->first()->name;
            $form_fields['product_id'] = $this->extra_product_id;
        } elseif($this->dispatch->job_id > 0){
            $form_fields['type'] = 'JDISP';
            $form_fields['comment'] = 'Dispatched for ' . ManufactureJobcards::where('id', $this->dispatch->job_id)->first()->jobcard_number;
            $form_fields['type_id'] = $this->manufacture_jobcard_product_id;
            $jobcard_product = ManufactureJobcardProducts::where('id', $this->manufacture_jobcard_product_id)->first();
            $form_fields['product_id'] = $jobcard_product->product()->id;
        }

        if($this->extra_product_weighed == 1){
            //Weighed Product takes Calculated Qty (Weigh Out - Weigh In)
            $form_fields['qty'] = $this->qty;
            $form_fields['weight_out'] = $this->weight_out;            
        } else {
            //Unweighed Product takes Input Qty    
            $form_fields['qty'] = $this->extra_product_qty;
            $form_fields['weight_out'] = $this->dispatch->weight_in;
        }

        if ($form_fields['product_id'] == '') {
            $this->extra_item_error = true;
            $this->extra_item_message = 'Please check Product is valid.';
        }

        if ($form_fields['qty'] <= '0') {
            $this->extra_item_error = true;
            $this->extra_item_message = 'Please check Qty is not less than or equal to 0.';
        }

        $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $this->manufacture_jobcard_product_id)->first();

        if ($manufacture_jobcard_product) {
            //Apply Variance of 500kg/ton on weighed items            
            $product_qty = $manufacture_jobcard_product->qty_due;
            //if ($form_fields['qty'] > $product_qty) { 2024-02-28 Variances            
            if (($form_fields['qty'] > $product_qty + 0.5 && $manufacture_jobcard_product->product()->weighed_product > 0)||($form_fields['qty'] > $product_qty && $manufacture_jobcard_product->product()->weighed_product == 0)) {
                $this->extra_item_error = true;
                $this->extra_item_message = "Qty is not allowed to be more than Qty allocated on Job for this Product (plus Variance for weighed products). Qty left on job card is {$product_qty}";
            } else {
                $form_fields['manufacture_jobcard_product_id'] = $this->manufacture_jobcard_product_id;
            }
        }


        if ($this->extra_item_error == false && $this->dispatch->status == 'Loading') {

            //Insert new line
            $form_fields['qty'] = Functions::negate($form_fields['qty']);
            ManufactureProductTransactions::insert($form_fields);

            //Get Qty Due and mark as Filled if required
            if ($manufacture_jobcard_product) {
                $product_qty = $manufacture_jobcard_product->qty_due;
                //Apply Variance of 500kg/ton on weighed items
                //if ($product_qty == 0) { 2024-02-28 Variances
                if (($product_qty == 0 && $manufacture_jobcard_product->product()->weighed_product == 0)||($product_qty <= 0.5 && $manufacture_jobcard_product->product()->weighed_product > 0)) {
                    ManufactureJobcardProducts::where('id', $manufacture_jobcard_product->id)->update(['filled' => 1]);
                    if ($product_qty <= 0.5 && $product_qty >= -0.5 && $product_qty != 0){
                        $this->over_under_variance[$manufacture_jobcard_product->id]='Product filled with Variance of '.number_format(Functions::negate($product_qty), 3).'.';
                    } else {
                        unset($this->over_under_variance[$manufacture_jobcard_product->id]);                        
                    }
                    // dd($this->over_under_variance);
                    //Set job card as Filled if filled <> 0
                    if (ManufactureJobcardProducts::where('job_id', $this->dispatch->jobcard()->id)->where('filled', '0')->count() == 0) {

                        ManufactureJobcards::where('id', $this->dispatch->jobcard()->id)->update(['status' => 'Filled']);
                    }
                }
            }            

            // Clear Extra Item Line after add
            $this->extra_product_id = '';
            $this->manufacture_jobcard_product_id = '';
            $this->extra_product_unit_measure = '';
            $this->extra_product_qty = 0;
            $this->extra_product_weight_in_date = '';
            $this->add_extra_item_show = false;
            $this->extra_product_weighed = '0';
            //Set error to blank
            $this->extra_item_error = false;
            $this->extra_item_message = '';
        }
    }

    public function render()
    {
        // List Open Jobcards with Product Due
        $jobcard_list = [];

        if ($this->dispatch->product() !== null) {
            $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
                ->where('status', 'Open')
                ->get()
                ->toArray();

            if (count($jobcard_list) > 0) {
                array_unshift($jobcard_list, ['value' => 0, 'name' => 'Select']);
            } else {
                $jobcard_list = [];
                array_unshift($jobcard_list, ['value' => 0, 'name' => 'No Jobcards found...']);
            }
        } else {
            $jobcard_list = [];

            $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
                ->where('status', 'Open')
                ->get()
                ->toArray();
        }


        array_unshift($jobcard_list, ['value' => 0, 'name' => 'Select']);


        $weighed_products = ManufactureProducts::select('id as product_id')->where('weighed_product', 1)->get()->toArray();

        $only_one_weighed = ManufactureProductTransactions::where('dispatch_id', $this->dispatch->id)
            ->whereIn('product_id', $weighed_products)->get()->count();

        if ($only_one_weighed > 0) {
            $only_one_weighed = true;
        } else {
            $only_one_weighed = false;
        }

        $manufacture_jobcard_products_list = [];
        $manufacture_jobcard_products_list_unweighed = [];

        if ($this->job_id > 0) {

            $manufacture_jobcard_products_list_unweighed = ManufactureJobcardProducts::select('manufacture_jobcard_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
                ->where('manufacture_jobcard_products.job_id', $this->job_id)
                ->where('manufacture_jobcard_products.filled', 0)
                ->where('manufacture_products.weighed_product', 0)
                ->join('manufacture_products', 'manufacture_products.id', 'manufacture_jobcard_products.product_id')
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();
        }



        array_unshift($manufacture_jobcard_products_list_unweighed, ['value' => 0, 'name' => 'Select']);

        //List of Delivery Zones
        $delivery_zone_list = SelectLists::zones_select;
        array_unshift($delivery_zone_list, ['value' => 0, 'name' => 'Select']);

        //List Cash Customers for External Collection / Delivery
        $customer_list = [];
        $customer_list = ManufactureCustomers::select('id as value', DB::raw("concat(account_number,' - ',name) as name"))
            ->get()
            ->toArray();


        if (count($customer_list) > 0) {

            array_unshift($customer_list, ['value' => 0, 'name' => 'Select Customer']);
        } else {
            $customer_list = [];
            array_unshift($customer_list, ['value' => 0, 'name' => 'No Customers found...']);
        }

        $products_list = [];
        $products_list_unweighed = [];
        //if($this->dispatch->id == '5'){dd('weighed:'.$weighed_dispatch_1.' only_one:'.$only_one_weighed_1);}

        $products_list_unweighed = ManufactureProducts::select('manufacture_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
            ->where('manufacture_products.weighed_product', 0)
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();

        $products_list = ManufactureProducts::select('manufacture_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
            ->where(function ($query) {
                $query->where('manufacture_products.weighed_product', 1);
            })
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();


        array_unshift($products_list, ['value' => 0, 'name' => 'Select']);
        array_unshift($products_list_unweighed, ['value' => 0, 'name' => 'Select']);

        // Items in Extra Items Table / Array
        $extra_items = [];

        //We may have Lines but they could not allocated to Customer or Jobcard yet

        $extra_items = ManufactureProductTransactions::select(
            'id',
            'dispatch_id',
            'weight_in_datetime as the_date',
            'weight_out',
            'manufacture_jobcard_product_id',
            'product_id',
            DB::raw('(select customer_id from manufacture_jobcard_product_dispatches where id= manufacture_product_transactions.dispatch_id) as customer_id'),            
            DB::raw('(select description from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as the_description'),
            DB::raw('(select unit_measure from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as the_unit'),
            DB::raw('(select weighed_product from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as weighed_product'),
            DB::raw('(select sum(qty) from manufacture_product_transactions as sumtable where sumtable.dispatch_id=manufacture_product_transactions.dispatch_id and sumtable.product_id=manufacture_product_transactions.product_id) as qty_due'),
            'qty as the_qty',
            'status'
        )            
            ->where('dispatch_id', $this->dispatch->id)
            ->get()
            ->toArray();
        
        /* $extra_items = $extra_items->addSelect(DB::raw('select sum(qty) as qty_due from manufacture_product_transactions where dispatch_id='.$this->dispatch->id.' and product_id='.$extra_items->product_id))
            ->toArray(); */

        $found_key = array_filter($extra_items, function($ar) {
            return ($ar['weighed_product'] == '1');        
        });        

        // dd($found_key);
        if(count($found_key)>0){
            //There are weighed products already in the list so dont allow them in SearchLivewire
            $this->weighedlist = '0';
        } else {
            //There are no weighed products already in the list so allow them in SearchLivewire
            $this->weighedlist = '1';
        }
            
        
        //Updated variables based on Extra Item Show
        if($this->add_extra_item_show == false){
            // Clear the Inputs
            $this->extra_product_id = '';
            $this->extra_product_unit_measure = '';
            $this->extra_product_qty = 0;
            $this->extra_product_weight_in_date = '';
            //Set error to blank
            $this->manufacture_jobcard_product_id = 0;
            // $this->extra_item_message = '';
        }

        // dd('render');        
        $over_under_variance_encoded = json_encode($this->over_under_variance);
        $over_under_variance_encoded = base64_encode($over_under_variance_encoded);


        return view('livewire.manufacture.dispatch.new-batch-out-modal', [
            'delivery_zone_list' => $delivery_zone_list,
            'jobcard_list' => $jobcard_list,
            'manufacture_jobcard_products_list' => $manufacture_jobcard_products_list,
            'manufacture_jobcard_products_list_unweighed' => $manufacture_jobcard_products_list_unweighed,
            'customer_list' => $customer_list,
            'customer_dispatch' => $this->customer_dispatch,
            'products_list' => $products_list,
            'products_list_unweighed' => $products_list_unweighed,
            'extra_items' => $extra_items,
            'add_extra_item_show' => $this->add_extra_item_show,
            'extra_product_unit_measure' => $this->extra_product_unit_measure,
            'extra_product_weight_in_date' => $this->extra_product_weight_in_date,
            'extra_product_qty' => $this->extra_product_qty,
            'extra_item_message' => $this->extra_item_message,
            'extra_item_error' => $this->extra_item_error,
            'only_one_weighed' => $only_one_weighed,
            'return_item_message' => $this->return_item_message,
            'return_item_success' => $this->return_item_success,
            'over_under_variance_encoded' => $over_under_variance_encoded,
        ]);
    }
}
