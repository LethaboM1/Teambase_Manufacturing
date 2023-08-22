<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Models\Plants;
use Livewire\Component;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureJobcardProducts;

class AddDispatchModal extends Component
{
    public $reference,
        $job_id,
        $jobcard,
        $manufacture_jobcard_product_id,
        $haulier_code,
        $delivery,
        $comment,
        $weight_in,
        $weight_in_datetime,
        $weight_out,
        $weight_out_datetime,
        $status,
        $plant_id,
        $registration_number,
        $batch_id,
        $qty;

    function mount()
    {
        $this->job_id = 0;
        $this->delivery = 0;
        $this->weight_in_datetime = date("Y-m-d\TH:i");
    }

    function updatedJobId($value)
    {
        if ($value > 0) {
            $this->jobcard = ManufactureJobcards::where('id', $value)->first();
            $this->delivery = $this->jobcard->delivery;
        }
    }

    function boot()
    {
    }

    public function render()
    {

        $jobcard_list = [];
        $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',contractor,', ',contact_person) as name"))->where('status', 'Open')->get()->toArray();
        array_unshift($jobcard_list, ['value' => 0, 'name' => 'Select']);

        $manufacture_jobcard_products_list = [];

        if ($this->job_id > 0) {
            $manufacture_jobcard_products_list = ManufactureJobcardProducts::select('manufacture_jobcard_products.id as value', DB::raw("concat(manufacture_products.code,' ',manufacture_products.description ) as name"))
                ->where('job_id', $this->job_id)
                ->whereIn('manufacture_jobcard_products.product_id', ManufactureBatches::select('product_id')->where('status', 'Ready for dispatch')->get())
                ->join('manufacture_products', 'manufacture_products.id', 'manufacture_jobcard_products.product_id')
                // ->join('manufacture_batch', 'manufacture_batch.id', 'manufacture_jobcard_products.batch_id')
                ->get()
                ->toArray();
        }
        // if ($this->job_id > 0) dd($manufacture_jobcard_products_list)
        array_unshift($manufacture_jobcard_products_list, ['value' => 0, 'name' => 'Select']);

        $plant_list = [];
        if ($this->delivery) $plant_list = Plants::select('plant_id as value', DB::raw("concat(plant_number,' ',make,' ',model) as name"))->get()->toArray();
        array_unshift($plant_list, ['value' => 0, 'name' => 'Select']);

        return view('livewire.manufacture.dispatch.add-dispatch-modal', [
            'jobcard_list' => $jobcard_list,
            'manufacture_jobcard_products_list' => $manufacture_jobcard_products_list,
            'plant_list' => $plant_list
        ]);
    }
}
