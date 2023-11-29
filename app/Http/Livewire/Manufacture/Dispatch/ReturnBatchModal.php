<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureJobcardProducts;

class ReturnBatchModal extends Component
{
    public $dispatch, $weight_in;

    function mount($dispatch)
    {
        $this->dispatch = $dispatch;
        $this->weight_in = 0;
    }

    public function render()
    {
        $jobcard_list = [];
        if ($this->dispatch->jobcard() !== null) {

            // $jobcard_list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' ',contractor,', ',contact_person) as name"))
            //     ->where('status', 'Open')
            //     ->where('jobcard_number', '<>', $this->dispatch->jobcard()->jobcard_number)
            //     ->whereIn('id', ManufactureJobcardProducts::select('job_id')->where('product_id', $this->dispatch->product()->id)->get())
            //     ->get()
            //     ->toArray();
        }

        if (count($jobcard_list) > 0) {
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'Select']);
        } else {
            $jobcard_list = [];
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'No Jobcards found...']);
        }

        return view('livewire.manufacture.dispatch.return-batch-modal', [
            'jobcard_list' => $jobcard_list
        ]);
    }
}
