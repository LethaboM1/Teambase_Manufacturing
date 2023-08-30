<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Models\Plants;
use Livewire\Component;
use App\Models\ManufactureBatches;
use App\Models\ManufactureJobcardProductDispatches;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureJobcardProducts;

class ReturnDispatchModal extends Component
{
    public $reference,
        $job_id,
        $dispatch,
        $manufacture_jobcard_dispatches_id,
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
        $qty,
        $yesterday,
        $today;

    function mount()
    {
        $this->job_id = 0;
        $this->delivery = 0;
        $this->weight_in_datetime = date("Y-m-d\TH:i");
        $yesterday = date_create(date('Y-m-d'));
        date_sub($yesterday, date_interval_create_from_date_string('1 day'));
        $this->yesterday = date_format($yesterday, 'Y-m-d 00:00:00');
        $this->today = date('Y-m-d H:i:s');
    }

    function updatedManufactureJobcardDispatchesId($value)
    {
        if ($value > 0) {
            $this->dispatch = ManufactureJobcardProductDispatches::where('id', $value)->first();
            $this->delivery = $this->dispatch->jobcard()->delivery;
        }
    }

    function boot()
    {
    }

    public function render()
    {



        // dd($batch);
        $manufacture_jobcard_dispatches = ManufactureJobcardProductDispatches::select('id as value', 'dispatch_number as name')
            ->where('status', 'Dispatched')
            ->where('weight_out_datetime', '>', $this->yesterday)
            ->where('weight_out_datetime', '<=', $this->today)
            ->get()
            ->toArray();


        array_unshift($manufacture_jobcard_dispatches, ['value' => 0, 'name' => 'Select']);

        return view('livewire.manufacture.dispatch.return-dispatch-modal', [
            'manufacture_jobcard_dispatches' => $manufacture_jobcard_dispatches,
        ]);
    }
}
