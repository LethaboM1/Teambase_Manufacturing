<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Models\ManufactureCustomers;
use App\Http\Controllers\SelectLists;
use function PHPUnit\Framework\isTrue;
use function PHPUnit\Framework\isFalse;

use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureProductTransactions;
use App\Models\ManufactureJobcardProductDispatches;

class NewBatchOutModal extends Component
{
    public $dispatch, $weight_out_datetime, $weight_out, $over_under_variance, $dispatch_temp, $dispatchaction, $qty, $qty_due, $job_id, $weight_in_datetime, $weight_in;
    public $jobcard, $delivery, $delivery_zone, $reference, $manufacture_jobcard_product_id, $extra_manufacture_jobcard_product_id;
    public $customer_dispatch, $customer_id, $product_id;
    public $add_extra_item_show, $extra_product_id, $extra_product_unit_measure, $extraproduct, $extra_product_qty, $extra_product_weight_in_date, $extra_item_message, $extra_item_error;
    public $dispatch_adjust_qty, $dispatch_return_weight_in, $return_item_success, $return_item_message, $return_extraitem_success, $return_extraitem_message;
    public $dispatch_transfer_weight_in, $transfer_item_success, $transfer_item_message, $transfer_extraitem_success, $transfer_extraitem_message, $transfer_job_id;

    public $listeners = ['removeExtraItem', 'returnExtraItem', 'transferExtraItem', 'addExtraItem', 'emitSet'];


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
                break;

            case 'manufacture_jobcard_product_id':

                $this->manufacture_jobcard_product_id = $value;
                self::updatedManufactureJobcardProductId($value);

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
        $this->over_under_variance = '';
        $this->manufacture_jobcard_product_id = $this->dispatch->manufacture_jobcard_product_id;
        $this->product_id = $this->dispatch->product_id;
        $this->dispatch_temp = $this->dispatch->dispatch_temp;
        $this->delivery_zone = $this->dispatch->delivery_zone;
        $this->qty = $this->dispatch->qty;
        $this->reference = $this->dispatch->reference;
        $this->job_id = $this->dispatch->job_id;
        $this->customer_id = $this->dispatch->customer_id;
        if($this->dispatch->manufacture_jobcard_product_id > 0){
            $jobcard = ManufactureJobcardProducts::where('id', $this->dispatch->manufacture_jobcard_product_id)->first();            
            $this->qty_due = $jobcard->qty_due;
        } else {
            $this->qty_due = 0;
        }
        

        if ($dispatch->customer_id == '0') {
            $this->customer_dispatch = 0;
        } else {
            $this->customer_dispatch = 1;
        }


        //for returns
        $this->weight_in_datetime = date("Y-m-d\TH:i");
        $this->weight_in = 0;
        $this->dispatchaction = $dispatchaction;
        $this->add_extra_item_show = 0;
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
        if ($value > 0) {
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'job_id' => $value,
                'customer_id' => 0,
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);

            $this->jobcard = ManufactureJobcards::where('id', $value)->first();
            $this->delivery = $this->jobcard->delivery;
            // Manufact
        }
    }

    function updatedCustomerId($value)
    {
        if ($value > 0) {
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'job_id' => 0,
                'customer_id' => $value,
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);
        }
    }

    function updatedReference($value)
    {
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'reference' => $value
        ]);
    }

    function updatedDeliveryZone($value)
    {
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'delivery_zone' => $value
        ]);
    }


    function updatedExtraProductQty()
    {
        $this->extra_item_error = false;
    }

    function updatingWeightOut($value)
    {
                
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'weight_out' => 0,
            'qty' => 0
        ]);        
        if($this->dispatch->jobcard_id > 0){
            $this->qty_due = $this->dispatch->jobcard_product()->qty_due;
        }
    }

    function updatedWeightOut($value)
    {
        
        if ($value < $this->dispatch->weight_in) return;

        $this->validate(['weight_out' => 'gt:0|lte:'.$this->qty_due + 0.5 + $this->dispatch->weight_in]);

        $this->qty = $value - $this->dispatch->weight_in;                
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'weight_out' => $value,
            'qty' => $this->qty
        ]);

        // if($this->qty_due){}
        
        if (($this->dispatch->jobcard_product()->qty_due == 0 && $this->dispatch->jobcard_product()->product()->weighed_product == 0)||($this->dispatch->jobcard_product()->qty_due <= 0.5 && $this->dispatch->jobcard_product()->product()->weighed_product > 0)) {    
            ManufactureJobcardProducts::where('id', $this->dispatch->jobcard_product()->id)->update(['filled' => 1]);            
            if ($this->dispatch->jobcard_product()->qty_due <= 0.5 && $this->dispatch->jobcard_product()->qty_due >= -0.5 && $this->dispatch->jobcard_product()->qty_due != 0){$this->over_under_variance='Product filled with Variance of '.number_format(Functions::negate($this->dispatch->jobcard_product()->qty_due), 3).'.';}else{$this->over_under_variance='';}
        } else {
            ManufactureJobcardProducts::where('id', $this->dispatch->jobcard_product()->id)->update(['filled' => 0]);
            $this->over_under_variance='';
        }
        
    }

    function updatedDispatchAdjustQty($value)
    {
        if ($value > $this->dispatch->qty || $value < 0) {
            $this->dispatch_adjust_qty = $this->dispatch->qty;
        }
    }

    function updatedDispatchTemp($value)
    {
        if ($value < 0) return;
        $this->dispatch_temp = $value;
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'dispatch_temp' => $value
        ]);
    }


    function updatedCustomerDispatch($value)
    {
        $this->manufacture_jobcard_product_id = 0;
        $this->customer_id = 0;
        $this->job_id = 0;

        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'customer_id' => 0,
            'job_id' => 0,
            'manufacture_jobcard_product_id' => 0
        ]);
    }

    function updatedExtraProductId($extra_product_id)
    {
        $this->extraproduct = ManufactureProducts::where('id', $extra_product_id)->get()->toArray();
        $this->extra_product_unit_measure = $this->extraproduct['0']['unit_measure'];
        $this->extra_product_weight_in_date = $this->dispatch->weight_in_datetime;
    }

    function updatedExtraManufactureJobcardProductId($value)
    {
        $jobcard = ManufactureJobcardProducts::where('id', $value)->first();
        $this->extraproduct = ManufactureProducts::where('id', $jobcard->product_id)->get()->toArray();
        $this->extra_product_id = $jobcard->product_id;
        $this->extra_product_unit_measure = $this->extraproduct['0']['unit_measure'];
        $this->extra_product_weight_in_date = $this->dispatch->weight_in_datetime;
    }

    function updatedProductId($value)
    {
        $this->manufacture_jobcard_product_id = 0;
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'manufacture_jobcard_product_id' => 0,
            'product_id' => $value
        ]);
    }

    function updatedManufactureJobcardProductId($value)
    {
        if ($value > 0) {
            $jobcard = ManufactureJobcardProducts::where('id', $value)->first();

            $this->product_id = $jobcard->product_id;

            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'manufacture_jobcard_product_id' => $value,
                'product_id' => $this->product_id
            ]);

            $this->qty_due = $jobcard->qty_due;
            
        } else {
            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'manufacture_jobcard_product_id' => 0,
                'product_id' => 0
            ]);
        }
    }

    function AddExtraItemShow()
    {

        if ($this->add_extra_item_show == '0') {
            //Show the Insert Line
            $this->add_extra_item_show = '1';
        } else {
            //Hide the Insert Line            
            $this->add_extra_item_show = '0';
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
                if (ManufactureJobcardProducts::where('job_id', $this->dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {

                    ManufactureJobcards::where('id', $this->dispatch->jobcard()->id)->update(['status' => 'Open']);
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
        ];
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
            $form_fields = [
                'qty' => $newqty
            ];

            if ($newqty > 0) {
                $form_fields['status'] = 'Partial Returned';
            } elseif ($newqty == 0) {
                $form_fields['status'] = 'Returned';
            }

            ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

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
                        'status' => ' '
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
                        'status' => ' '
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

        $extra_item = ManufactureProductTransactions::where('id', $extra_item_id)->first();
        $manufacture_jobcard_product_id = $extra_item->manufacture_jobcard_product_id;
        $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $manufacture_jobcard_product_id)->first();

        $dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();

        $error = false;

        $returnqty = $adjust_qty;

        //Compare what was dispatched with what is being returned
        $product_qty = $extra_item->qty;

        $newqty = $product_qty + $returnqty;

        if (!$error) {
            $form_fields = [
                'qty' => $newqty
            ];

            if ($newqty > 0) {
                $form_fields['status'] = 'Partial Returned';
            } elseif ($newqty == 0) {
                $form_fields['status'] = 'Returned';
            }


            ManufactureProductTransactions::where('id', $extra_item->id)->update($form_fields);

            //If Jobcard Dispatch

            if ($dispatch->customer_id == '0') {
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

                //Returned Raw Product Transaction
                if ($manufacture_jobcard_product->product()->has_recipe == 0) {
                    //Adjust transaction if no recipe
                    $form_fields = [
                        'product_id' => $manufacture_jobcard_product->product()->id,
                        'type_id' => $dispatch->id,
                        // 'qty' => $returnqty,                        
                        'user_id' => auth()->user()->user_id,
                        'status' => ' '
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
                if ($extra_item->customer_product()->has_recipe == 0) {
                    //Adjust transaction if no recipe
                    $form_fields = [
                        'product_id' => $extra_item->customer_product()->id,
                        'type_id' => $dispatch->id,
                        // 'qty' => $returnqty,                        
                        'user_id' => auth()->user()->user_id,
                        'status' => ' '
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


            $this->return_extraitem_success = true;

            if ($dispatch->customer_id == '0') {
                $this->return_extraitem_message = "Dispatch No. " . $dispatch->dispatch_number . " has been returned. Job " . $dispatch->jobcard()->jobcard_number . " has been credited with " . $returnqty . " " . $manufacture_jobcard_product->product()->description;
                $this->dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();

                return;
            } else {
                $this->return_extraitem_message = "Dispatch No. " . $dispatch->dispatch_number . " has been returned. " . $returnqty . " " . $extra_item->customer_product()->description . " has been credited.";
                $this->dispatch = ManufactureJobcardProductDispatches::where('id', $extra_item->dispatch_id)->first();

                return;
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

    function confirmTransferItem($id)
    {

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

        $form_fields['qty'] = $this->extra_product_qty;


        $form_fields['product_id'] = $this->extra_product_id;
        $form_fields['weight_out'] = $this->dispatch->weight_in;

        if ($form_fields['product_id'] == '') {
            $this->extra_item_error = true;
            $this->extra_item_message = 'Please check Product is valid.';
        }

        if ($form_fields['qty'] <= '0') {
            $this->extra_item_error = true;
            $this->extra_item_message = 'Please check Qty is not less than or equal to 0.';
        }

        $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $this->extra_manufacture_jobcard_product_id)->first();


        if ($manufacture_jobcard_product) {
            //Apply Variance of 500kg/ton on weighed items
            $product_qty = $manufacture_jobcard_product->qty_due;
            //if ($form_fields['qty'] > $product_qty) { 2024-02-28 Variances
            if (($form_fields['qty'] > $product_qty + 0.5 && $manufacture_jobcard_product->product()->weighed_product > 0)||($form_fields['qty'] > $product_qty && $manufacture_jobcard_product->product()->weighed_product == 0)) {
                $this->extra_item_error = true;
                $this->extra_item_message = "Qty is not allowed to be more than Qty allocated on Job for this Product (plus Variance for weighed products). Qty left on job card is {$product_qty}";
            } else {
                $form_fields['manufacture_jobcard_product_id'] = $this->extra_manufacture_jobcard_product_id;
            }
        }


        if ($this->extra_item_error == false) {

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

                    //Set job card as Filled if filled <> 0
                    if (ManufactureJobcardProducts::where('job_id', $this->dispatch->jobcard()->id)->where('filled', '0')->count() == 0) {

                        ManufactureJobcards::where('id', $this->dispatch->jobcard()->id)->update(['status' => 'Filled']);
                    }
                }
            }

            // Clear Extra Item Line after add
            $this->extra_product_id = '';
            $this->extra_product_unit_measure = '';
            $this->extra_product_qty = 0;
            $this->extra_product_weight_in_date = '';
            $this->add_extra_item_show = '0';
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
            DB::raw('(select description from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as the_description'),
            DB::raw('(select unit_measure from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as the_unit'),
            'qty as the_qty'
        )
            ->where('dispatch_id', $this->dispatch->id)
            ->get()
            ->toArray();




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
        ]);
    }
}
