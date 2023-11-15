<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Models\ManufactureCustomers;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcardProductDispatchDeliveryzones;

class NewBatchOutModal extends Component
{
    public $dispatch, $weight_out_datetime, $weight_out, $dispatch_temp, $dispatchaction, $qty, $job_id, $weight_in_datetime, $weight_in;
    public $jobcard, $delivery, $delivery_zone, $reference, $manufacture_jobcard_product_id;
    public $customer_dispatch, $customer_id, $product_id;


    function mount($dispatch, $dispatchaction)
    {
        $this->dispatch = $dispatch;
        $this->weight_out_datetime = date("Y-m-d\TH:i");
        $this->weight_out = 0;
        $this->dispatch_temp = 0;
        $this->qty = 0;
        $this->job_id = 0;
        if ($dispatch->customer_id == '0'){
            $this->customer_dispatch = 0;
        } else {
            $this->customer_dispatch = 1;
        }         

        //for returns
        $this->weight_in_datetime = date("Y-m-d\TH:i");
        $this->weight_in = 0;
        $this->dispatchaction = $dispatchaction;
        
    }

    function updatedJobId($value)
    {
        if ($value > 0) {
            $this->jobcard = ManufactureJobcards::where('id', $value)->first();
            $this->delivery = $this->jobcard->delivery;
            // dd($this->delivery);
        }
    }


    function updatedWeightOut($value)
    {
        if ($value < $this->dispatch->weight_in) return;
        $this->qty = $value - $this->dispatch->weight_in;
    }

    function updatedCustomerDispatch($value)
    {        
        $this->customer_dispatch = $value;
        //dd($this->customer_dispatch);
    }

    function updatedCustomerProductId($value)
    {        
        $this->product_id = $value;
        //dd($this->product_id);
    }

    public function render()
    {
        // List Open Jobcards with Product Due
        $jobcard_list = [];

        if ($this->dispatch->product() !== null) {
            $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
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
        } else {
            $jobcard_list = [];

            $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
                ->where('status', 'Open')
                ->get()
                ->toArray();
        }


        array_unshift($jobcard_list, ['value' => 0, 'name' => 'Select']);

        $manufacture_jobcard_products_list = [];

        if ($this->job_id > 0) {
            $raw_products = ManufactureProducts::select('id as product_id')->where('has_recipe', 0)->get()->toArray();
            //dd($raw_products);
            $batches = ManufactureBatches::select('product_id', 'id', 'qty')->where('status', 'Ready for dispatch')->get()->filter(function ($batch) {
                return $batch->qty_left > 0;
            });

            foreach ($batches as $item) $batch[] = $item->product_id;
            if (!isset($batch)) $batch = [];

            // dd($batch);
            $manufacture_jobcard_products_list = ManufactureJobcardProducts::select('manufacture_jobcard_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
                ->where('manufacture_jobcard_products.job_id', $this->job_id)
                ->where('manufacture_jobcard_products.filled', 0)
                ->where(function ($query) use ($raw_products, $batch) {
                    $query->whereIn('manufacture_jobcard_products.product_id', $batch)
                        ->orWhereIn('manufacture_jobcard_products.product_id', $raw_products);
                })
                ->join('manufacture_products', 'manufacture_products.id', 'manufacture_jobcard_products.product_id')
                // ->join('manufacture_batch', 'manufacture_batch.id', 'manufacture_jobcard_products.batch_id')
                ->get()
                ->toArray();

            //dd($manufacture_jobcard_products_list);
        }


        array_unshift($manufacture_jobcard_products_list, ['value' => 0, 'name' => 'Select']);

        //List of Delivery Zones
        $delivery_zone_list = SelectLists::zones_select; //ManufactureJobcardProductDispatchDeliveryzones::select('code as value', DB::raw("description as name"))->get()->toArray();
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

        $products_list = ManufactureProducts::select('manufacture_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
            ->get()
            ->toArray();

        array_unshift($products_list, ['value' => 0, 'name' => 'Select']);

        return view('livewire.manufacture.dispatch.new-batch-out-modal', [
            'delivery_zone_list' => $delivery_zone_list,
            'jobcard_list' => $jobcard_list,
            'manufacture_jobcard_products_list' => $manufacture_jobcard_products_list,
            'customer_list' => $customer_list,
            'customer_dispatch' => $this->customer_dispatch,
            'products_list' => $products_list
        ]);
    }
}
