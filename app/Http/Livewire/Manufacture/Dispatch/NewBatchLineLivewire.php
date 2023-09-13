<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use Livewire\Component;

class NewBatchLineLivewire extends Component
{
    public $dispatch, $weight_out, $weight_out_datetime, $qty, $dispatchaction, $weight_in_datetime, $weight_in, $dispatch_temp;

    function mount($dispatch)
    {
        $this->dispatch = $dispatch;
        $this->weight_out_datetime = date("Y-m-d\TH:i");
        $this->weight_out = 0;
        $this->dispatch_temp = 0;
        $this->qty = 0;
        //for returns
        $this->weight_in_datetime = date("Y-m-d\TH:i");
        $this->weight_in = 0;
    }    

    function updatedWeightOut($value)
    {
        if ($value < $this->dispatch->weight_in) return;
        $this->qty = $value - $this->dispatch->weight_in;
    }

    public function render()
    {
        return view('livewire.manufacture.dispatch.new-batch-line-livewire');
    }
}
