<?php

namespace App\Http\Livewire\Manufacture\Batches\Create;

use App\Models\ManufactureProducts;
use Livewire\Component;

class JobItem extends Component
{
    public $jobcard, $product, $toggle, $check, $job_product, $qty;

    function mount($jobcards, $jobcard, $product)
    {
        $this->jobcard = $jobcard;
        $this->check = (in_array($jobcard['id'], array_column($jobcards, 'id')) ? 1 : 0);
        $this->qty = $product->qty;

        $product_ = ManufactureProducts::where('id', $product->product_id)->first();
        $this->product = $product_;
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
