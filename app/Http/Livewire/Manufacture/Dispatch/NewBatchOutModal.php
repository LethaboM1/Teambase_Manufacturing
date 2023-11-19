<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Http\Controllers\Functions;
use Livewire\Component;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Models\ManufactureCustomers;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureProductTransactions;

use function PHPUnit\Framework\isFalse;
use function PHPUnit\Framework\isTrue;

class NewBatchOutModal extends Component
{
    public $dispatch, $weight_out_datetime, $weight_out, $dispatch_temp, $dispatchaction, $qty, $job_id, $weight_in_datetime, $weight_in;
    public $jobcard, $delivery, $delivery_zone, $reference, $manufacture_jobcard_product_id, $extra_manufacture_jobcard_product_id;
    public $customer_dispatch, $customer_id, $product_id;
    public $add_extra_item_show, $extra_product_id, $extra_product_unit_measure, $extraproduct, $extra_product_qty, $extra_product_weight_in_date, $extra_item_message, $extra_item_error;

    public $listeners = ['removeExtraItem', 'addExtraItem'];


    function mount($dispatch, $dispatchaction)
    {
        $this->dispatch = $dispatch;
        $this->weight_out_datetime = date("Y-m-d\TH:i");
        $this->weight_out = $dispatch->weight_out;
        $this->manufacture_jobcard_product_id = $dispatch->manufacture_jobcard_product_id;
        $this->product_id = $dispatch->product_id;
        $this->dispatch_temp = $dispatch->dispatch_temp;
        $this->delivery_zone = $dispatch->delivery_zone;
        $this->qty = $dispatch->qty;
        $this->reference = $dispatch->reference;
        $this->job_id = $this->dispatch->job_id;
        $this->customer_id = $this->dispatch->customer_id;

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

    function updatedWeightOut($value)
    {
        if ($value < $this->dispatch->weight_in) return;
        $this->qty = $value - $this->dispatch->weight_in;
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'weight_out' => $value,
            'qty' => $this->qty
        ]);
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
        $this->customer_dispatch = $value;
        ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
            'customer_id' => 0,
            'job_id' => 0
        ]);
    }

    // function updatedCustomerProductId($value)
    // {
    //     $this->product_id = $value;
    // }

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

    function updatedManufactureJobcardProductId($value)
    {
        if ($value > 0) {
            $jobcard = ManufactureJobcardProducts::where('id', $value)->first();

            $this->product_id = $jobcard->product_id;

            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update([
                'manufacture_jobcard_product_id' => $value,
                'product_id' => $this->product_id
            ]);
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

        //ManufactureJobcardProductDispatches::where('id', $extra_item_id)->delete();

        $extra_item = ManufactureProductTransactions::where('id', $extra_item_id)->first();
        $manufacture_jobcard_product_id = $extra_item->manufacture_jobcard_product_id;

        //Delete Extra Item
        ManufactureProductTransactions::where('id', $extra_item_id)->delete();

        $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $manufacture_jobcard_product_id)->first();
        if ($manufacture_jobcard_product) {
            //dd($jobcard);
            $product_qty = $manufacture_jobcard_product->qty_due;
            //dd('id:'.$manufacture_jobcard_product_id.'due:'.$product_qty);

            if ($product_qty > 0) {
                ManufactureJobcardProducts::where('id', $manufacture_jobcard_product_id)->update(['filled' => 0]);
            }

            /* if (ManufactureJobcardProducts::where('job_id', $jobcard->id)->where('filled', '0')->count() == 0) {

                ManufactureJobcards::where('id', $jobcard->id)->update(['status' => 'Completed']);
            } */
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
            "registration_number" => $this->dispatch->registration_number,
            "status" => "Loading",
            "weight_out_user" => auth()->user()->user_id,
            "weight_out_datetime" => date("Y-m-d\TH:i"),
            "weight_in_user" => auth()->user()->user_id,
            "weight_in_datetime" => date("Y-m-d\TH:i"),
            "dispatch_temp" => $this->dispatch_temp,
            "user_id" => auth()->user()->user_id,
        ];

        $form_fields['qty'] = $this->extra_product_qty;

        if ($this->dispatch->weight_in == 0) {
            $form_fields['product_id'] = $this->extra_product_id;
            $form_fields['weight_out'] = $this->dispatch->weight_in;
        } else {
            $form_fields['weight_out'] = $this->weight_out;
            $form_fields['product_id'] = $this->extra_product_id;
        }

        //dd($form_fields);



        if ($form_fields['product_id'] == '') {
            $this->extra_item_error = true;
            $this->extra_item_message = 'Please check Product is valid.';
        }

        if ($form_fields['qty'] <= '0') {
            $this->extra_item_error = true;
            $this->extra_item_message = 'Please check Qty is not less than or equal to 0.';
        }

        $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $this->manufacture_jobcard_product_id)->first();
        //dd($jobcard->qty_due);

        if ($manufacture_jobcard_product) {
            $product_qty = $manufacture_jobcard_product->qty_due;
            if ($form_fields['qty'] > $product_qty) {
                $this->extra_item_error = true;
                $this->extra_item_message = "Qty is not allowed to be more than Qty allocated on Job for this Product. Qty left on job card is {$product_qty}";
            } else {
                $form_fields['manufacture_jobcard_product_id'] = $this->manufacture_jobcard_product_id;
            }
        }

        //dd($this->extra_item_error);
        if ($this->extra_item_error == false) {

            //Insert new line             
            // ManufactureJobcardProductDispatches::insert($form_fields);
            $form_fields['qty'] = Functions::negate($form_fields['qty']);
            ManufactureProductTransactions::insert($form_fields);

            //Get Qty Due and mark as Filled if required
            if ($manufacture_jobcard_product) {
                $product_qty = $manufacture_jobcard_product->qty_due;

                if ($product_qty == 0) {
                    ManufactureJobcardProducts::where('id', $manufacture_jobcard_product->id)->update(['filled' => 1]);
                }

                /* if (ManufactureJobcardProducts::where('job_id', $jobcard->id)->where('filled', '0')->count() == 0) {

                    ManufactureJobcards::where('id', $jobcard->id)->update(['status' => 'Completed']);
                } */
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
                //->where('jobcard_number', '<>', $this->dispatch->jobcard()->jobcard_number)
                //->whereIn('id', ManufactureJobcardProducts::select('job_id')->where('product_id', $this->dispatch->product()->id)->get())
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

        if ($this->dispatch->weight_in > 0) {
            $weighed_dispatch = true;
        } else {
            $weighed_dispatch = false;
        }

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
                ->where('manufacture_products.has_recipe', 0)
                ->join('manufacture_products', 'manufacture_products.id', 'manufacture_jobcard_products.product_id')
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();

            $manufacture_jobcard_products_list = ManufactureJobcardProducts::select('manufacture_jobcard_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
                ->where('manufacture_jobcard_products.job_id', $this->job_id)
                ->where('manufacture_jobcard_products.filled', 0)
                ->where(function ($query) {
                    $query->where('manufacture_products.weighed_product', 1)
                        ->orWhere('manufacture_products.has_recipe', 1);
                })
                ->join('manufacture_products', 'manufacture_products.id', 'manufacture_jobcard_products.product_id')
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();
        }


        array_unshift($manufacture_jobcard_products_list, ['value' => 0, 'name' => 'Select']);
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
            ->where('manufacture_products.has_recipe', 0)
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();

        $products_list = ManufactureProducts::select('manufacture_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
            ->where(function ($query) {
                $query->where('manufacture_products.weighed_product', 1)
                    ->orWhere('manufacture_products.has_recipe', 1);
            })
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();


        array_unshift($products_list, ['value' => 0, 'name' => 'Select']);
        array_unshift($products_list_unweighed, ['value' => 0, 'name' => 'Select']);

        // Items in Extra Items Table / Array
        $extra_items = [];

        //We may have Lines but they could not allocated to Customer or Jobcard yet
        //$extra_items = ManufactureJobcardProductDispatches::select('id', 'dispatch_group_id', 'weight_in_datetime as the_date',
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

        //Set weight if Line exists already
        if ($this->dispatch->weight_in > 0 && count($extra_items) > 0) {
            $this->weight_out = $extra_items[0]['weight_out'];
        }


        return view('livewire.manufacture.dispatch.new-batch-out-modal', [
            'delivery_zone_list' => $delivery_zone_list,
            'jobcard_list' => $jobcard_list,
            'manufacture_jobcard_products_list' => $manufacture_jobcard_products_list,
            'manufacture_jobcard_products_list_unweighed' => $manufacture_jobcard_products_list_unweighed,
            'customer_list' => $customer_list,
            'customer_dispatch' => $this->customer_dispatch,
            'products_list' => $products_list,
            'products_list_unweighed' => $products_list_unweighed,
            //'extra_items_show' => $this->extra_items_show,
            'extra_items' => $extra_items,
            'add_extra_item_show' => $this->add_extra_item_show,
            'extra_product_unit_measure' => $this->extra_product_unit_measure,
            'extra_product_weight_in_date' => $this->extra_product_weight_in_date,
            'extra_product_qty' => $this->extra_product_qty,
            'extra_item_message' => $this->extra_item_message,
            'extra_item_error' => $this->extra_item_error,
            'only_one_weighed' => $only_one_weighed
        ]);
    }
}
