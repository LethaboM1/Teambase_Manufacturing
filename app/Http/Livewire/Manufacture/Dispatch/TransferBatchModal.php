<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Models\Plants;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureJobcardProducts;

class TransferBatchModal extends Component
{
    public $dispatch, $delivery, $job_id, $jobcard, $delivery_zone;

    function mount($dispatch)
    {
        $this->dispatch = $dispatch;
        $this->job_id = 0;
    }

    function updatedJobId($value)
    {
        if ($value > 0) {
            $this->jobcard = ManufactureJobcards::where('id', $value)->first();
            $this->delivery = $this->jobcard->delivery;
            // dd($this->delivery);
        }
    }

    public function render()
    {
        $jobcard_list = [];
        if ($this->dispatch->jobcard() !== null) {
            $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',IFNULL(contractor,''),', ',IFNULL(contact_person,'')) as name"))
                ->where('status', 'Open')
                ->where('jobcard_number', '<>', $this->dispatch->jobcard()->jobcard_number)
                ->whereIn('id', ManufactureJobcardProducts::select('job_id')->where('product_id', $this->dispatch->product()->id)->get())
                ->get()
                ->toArray();
        }

        if (count($jobcard_list) > 0) {
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'Select']);
        } else {
            $jobcard_list = [];
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'No Jobcards found...']);
        }

        $delivery_zone_list = [];
        if ($this->delivery) $delivery_zone_list = SelectLists::zones_select; //ManufactureJobcardProductDispatchDeliveryzones::select('code as value', DB::raw("description as name"))->get()->toArray();
        array_unshift($delivery_zone_list, ['value' => 0, 'name' => 'Select']);

        return view('livewire.manufacture.dispatch.transfer-batch-modal', [
            'jobcard_list' => $jobcard_list,
            'delivery_zone_list' => $delivery_zone_list

        ]);
    }
}
