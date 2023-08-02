<?php

namespace App\Http\Livewire\Manufacture\Dispatch\BatchAllocate;

use Livewire\Component;

class JobItem extends Component
{
    public $check, $jobcard, $batch, $jobcardproduct, $qty_from, $qty, $batch_qty;

    function mount($jobcardproduct, $batch)
    {
        $this->jobcardproduct = $jobcardproduct;
        $this->jobcard = $this->jobcardproduct->jobcard();
        $this->batch = $batch;
        $this->qty = 0;
        $this->qty_from = 0;
    }

    function getListeners()
    {
        return [
            "setQty:{$this->jobcardproduct->id}" => 'setQty',
            "updateItems" => '$refresh'
        ];
    }

    function setQty($value)
    {
        if ($value > $this->jobcardproduct->qty_due) {
            $this->qty = $this->jobcardproduct->qty_due;
            $this->emit('checkProduct', $this->jobcardproduct->id, $value, $this->qty);
        } else {
            $this->qty = $value;
        }
    }

    function updatingCheck($value)
    {
        $this->emit('checkProduct', $this->jobcardproduct->id, $value, $this->qty);
    }

    function updatingQty($value)
    {
        if (!is_numeric($value)) return;
        $this->qty_from = $this->qty;
    }

    function updatedQty($value)
    {
        if (!is_numeric($value)) return;

        if ($value > $this->jobcardproduct->qty_due) {
            $this->qty = $this->jobcardproduct->qty_due;
        }

        $this->emit('changeProduct', $this->jobcardproduct->id, $this->qty_from,  $this->qty);
    }


    public function render()
    {
        return view('livewire.manufacture.dispatch.batch-allocate.job-item');
    }
}
