<?php

namespace App\Http\Livewire\Manufacture\Batches\Create;

use App\Models\ManufactureProducts;
use Livewire\Component;

class JobItem extends Component
{
    public $jobcard, $jobcardproduct, $product, $toggle, $check, $job_product, $qty;

    function mount($jobcards, $jobcardproduct)
    {
        $this->jobcardproduct = $jobcardproduct;
        $this->jobcard = $this->jobcardproduct->jobcard();
        $this->product = $this->jobcardproduct->product();

        $this->check = (in_array($this->jobcard['id'], array_column($jobcards, 'id')) ? 1 : 0);
        $this->qty = $this->jobcardproduct->qty;
    }

    function updatedCheck($value)
    {
        if ($value != 1) $value = 0;

        $this->emitUp('select_job', [
            'id' => $this->jobcard['id'],
            'qty' => $this->qty,
            'checked' => $value
        ]);
    }

    public function render()
    {
        return view('livewire.manufacture.batches.create.job-item');
    }
}
