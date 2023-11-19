<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Models\Plants;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Models\ManufactureCustomers;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureProductTransactions;

class AddDispatchAdditionalModal extends Component
{
    public
        $reference = '',
        $delivery,
        $weight_in,
        $weight_in_datetime,
        $weight_out,
        $weight_out_datetime,
        $status,
        $plant_id = 0,
        $registration_number = '',
        $batch_id,
        $qty,
        $delivery_zone,
        $dispatchaction;

    public $customer_dispatch, $customer_id, $product_id, $job_id, $add_extra_item_show = 0, $extra_item_error = false, $extra_item_message, $extraproduct;

    public $extra_items = [], $extra_product_id, $extra_product_unit_measure, $extra_product_qty, $extra_product_weight_in_date, $manufacture_jobcard_product_id;

    function mount()
    {

        $this->delivery = 0;
        $this->weight_in_datetime = date("Y-m-d\TH:i");
        $this->customer_dispatch = 0;
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
            //Set error to blank
            $this->manufacture_jobcard_product_id = 0;
            $this->extra_item_message = '';
        }
    }

    function updatedDelivery($value)
    {
        $this->delivery = $value;
    }

    function removeExtraItem($key)
    {
        unset($this->extra_items[$key]);
        $this->extra_items = array_keys($this->extra_items);
    }

    function AddExtraItem()
    {
        $this->extra_item_error = false;
        $this->extra_item_message = '';



        $form_fields = [
            // "dispatch_id" => $dispatch_id,
            // "reference_number" => $this->reference,
            "weight_in" => 0,
            // "registration_number" => $this->registration_number,
            "status" => "Dispatched",
            // "weight_out_user" => auth()->user()->user_id,
            // "weight_out_datetime" => date("Y-m-d\TH:i"),
            "weight_in_user" => auth()->user()->user_id,
            "weight_in_datetime" => date("Y-m-d\TH:i"),
            // "dispatch_temp" => $this->dispatch_temp,
            "user_id" => auth()->user()->user_id,
        ];

        $form_fields['qty'] = $this->extra_product_qty;

        $form_fields['product_id'] = $this->extra_product_id;

        if ($form_fields['product_id'] == '') {
            $this->extra_item_error = true;
            $this->extra_item_message = 'Please check Product is valid.';
            return;
        }

        if ($form_fields['qty'] <= 0) {
            $this->extra_item_error = true;
            $this->extra_item_message = 'Please check Qty is not less than or equal to 0.';
            return;
        }

        $product = ManufactureProducts::where('id', $form_fields['product_id'])->first();
        $form_fields['description'] = "{$product['code']} {$product['description']}";
        $form_fields['unit_measure'] = "{$product['unit_measure']}";

        $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $this->manufacture_jobcard_product_id)->first();

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
            // ManufactureProductTransactions::insert($form_fields);

            $this->extra_items[] = $form_fields;

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

    function dispatch()
    {
        $form_fields['plant_id'] = $this->plant_id;
        $form_fields['job_id'] = $this->job_id;
        $form_fields['customer_id'] = $this->customer_id;
        $form_fields['reference'] = $this->reference;
        $form_fields['product_id'] = 0;
        $form_fields['manufacture_jobcard_product_id'] = 0;
        $form_fields['qty'] = 0;

        $form_fields['registration_number'] = $this->registration_number;

        if ($form_fields['plant_id'] > 0 && $this->delivery_zone == 0) return back()->with('alertError', 'For a delivery you must choose a delivery zone.');

        if ($form_fields['registration_number'] == '' && $form_fields['plant_id'] == 0) return back()->with('alertError', 'Plant/Reg No must be selected/filled.');

        if (isset($form_fields['registration_number'])) {
            $plant = $form_fields['registration_number'];
        } else {
            $plant = Plants::where('plant_id', $form_fields['plant_id'])->first();
            if ($plant == null) return back()->with('alertError', 'Plant not found.');
            $plant = $plant->toArray();
            $plant = "{$plant['plant_number']} {$plant['make']} {$plant['model']} {$plant['reg_number']}";
        }

        $form_fields['status'] = 'Dispatched';
        $form_fields['weight_in'] = '0';
        $form_fields['weight_in_user_id'] = auth()->user()->user_id;
        $form_fields['weight_in_datetime'] = date("Y-m-d\TH:i");

        $form_fields['weight_out'] = '0';
        $form_fields['weight_out_user_id'] = auth()->user()->user_id;
        $form_fields['weight_out_datetime'] = date("Y-m-d\TH:i");

        $form_fields['dispatch_number'] =  Functions::get_doc_number('dispatch');


        $dispatch_id = ManufactureJobcardProductDispatches::insertGetId($form_fields);

        if (count($this->extra_items)) {
            foreach ($this->extra_items as $item) {
                unset($item['description']);
                unset($item['unit_measure']);
                $item['dispatch_id'] = $dispatch_id;
                // $item['registration_number'] = $this->registration_number;
                // $item['reference'] = $this->reference;
                ManufactureProductTransactions::insert($item);

                if (!empty($item['manufacture_jobcard_product_id']) && $item['manufacture_jobcard_product_id'] > 0) {
                    $manufacture_jobcard_product = ManufactureJobcardProducts::where('id', $item['manufacture_jobcard_product_id'])->first();

                    if ($manufacture_jobcard_product) {
                        $product_qty = $manufacture_jobcard_product->qty_due;
                        if ($product_qty == 0) {
                            ManufactureJobcardProducts::where('id', $manufacture_jobcard_product->id)->update(['filled' => 1]);
                        }
                    }
                }
            }
        }

        $this->extra_items = [];
        // Clear Extra Item Line after add
        $this->job_id = 0;
        $this->plant_id = 0;
        $this->customer_id = 0;
        $this->reference = '';
        $this->registration_number = '';

        $this->extra_product_id = '';
        $this->extra_product_unit_measure = '';
        $this->extra_product_qty = 0;
        $this->extra_product_weight_in_date = '';
        $this->add_extra_item_show = '0';
        //Set error to blank
        $this->extra_item_error = false;
        $this->extra_item_message = '';
        $this->emit('closeDispatch', $dispatch_id);
        $this->delivery = 0;
        $this->weight_in_datetime = date("Y-m-d\TH:i");
        $this->customer_dispatch = 0;
        $this->delivery_zone = 0;
    }

    function updatedCustomerDispatch()
    {
        $this->extra_items = [];
    }

    function updatingCustomerId()
    {
        if ($this->customer_id == 0) {
            $this->extra_items = [];
        }
    }

    function updatedCustomerId()
    {
        $this->job_id = 0;
    }

    function updatedJobId()
    {
        $this->customer_id = 0;
        $this->extra_items = [];
    }

    function updatedExtraProductId($extra_product_id)
    {
        $this->manufacture_jobcard_product_id = 0;
        $this->extraproduct = ManufactureProducts::where('id', $extra_product_id)->get()->toArray();
        $this->extra_product_unit_measure = $this->extraproduct['0']['unit_measure'];
        // $this->extra_product_weight_in_date = $this->dispatch->weight_in_datetime;
    }

    function updatedManufactureJobcardProductId($value)
    {
        $this->extra_product_id = 0;
        $jobcard = ManufactureJobcardProducts::where('id', $value)->first();
        $this->extraproduct = ManufactureProducts::where('id', $jobcard->product_id)->get()->toArray();
        $this->extra_product_id = $jobcard->product_id;
        $this->extra_product_unit_measure = $this->extraproduct['0']['unit_measure'];
        // $this->extra_product_weight_in_date = $this->dispatch->weight_in_datetime;
    }


    public function render()
    {
        $products_list = [];
        if ($this->customer_id > 0) {
            //if($this->dispatch->id == '5'){dd('weighed:'.$weighed_dispatch_1.' only_one:'.$only_one_weighed_1);}

            $products_list = ManufactureProducts::select('manufacture_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
                ->where('weighed_product', 0)
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();

            array_unshift($products_list, ['value' => 0, 'name' => 'Select']);
        }

        $manufacture_jobcard_products_list = [];

        if ($this->job_id > 0) {

            $manufacture_jobcard_products_list = ManufactureJobcardProducts::select('manufacture_jobcard_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
                ->where('manufacture_jobcard_products.job_id', $this->job_id)
                ->where('manufacture_jobcard_products.filled', 0)
                ->whereIn('manufacture_jobcard_products.product_id', ManufactureProducts::select('id as product_id')->where('weighed_product', 0))
                ->join('manufacture_products', 'manufacture_products.id', 'manufacture_jobcard_products.product_id')
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();

            array_unshift($manufacture_jobcard_products_list, ['value' => 0, 'name' => 'Select']);
        }

        $plant_list = [];
        if ($this->delivery) $plant_list = Plants::select('plant_id as value', DB::raw("concat(plant_number,' ',make,' ',model) as name"))->orderBy('plant_number')->get()->toArray();
        array_unshift($plant_list, ['value' => 0, 'name' => 'Select']);

        $jobcard_list = [];

        $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
            ->where('status', 'Open')
            ->get()
            ->toArray();

        if (count($jobcard_list) > 0) {

            array_unshift($jobcard_list, ['value' => 0, 'name' => 'Select Job Card']);
        } else {
            $jobcard_list = [];
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'No Job Cards found...']);
        }

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

        $delivery_zone_list = SelectLists::zones_select;
        array_unshift($delivery_zone_list, ['value' => 0, 'name' => 'Select']);

        return view('livewire.manufacture.dispatch.add-dispatch-additional-modal', [
            'plant_list' => $plant_list,
            'jobcard_list' => $jobcard_list,
            'customer_list' => $customer_list,
            'delivery_zone_list' => $delivery_zone_list,
            'manufacture_jobcard_products_list' => $manufacture_jobcard_products_list,
            'products_list' => $products_list
            // 'weighed_dispatch' => $this->weighed_dispatch
        ]);
    }
}
