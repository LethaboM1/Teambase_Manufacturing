<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use App\Models\ManufactureProducts;
use Livewire\Component;

class Item extends Component
{
    public $item, $product;

    function mount($item)
    {
        $this->item = $item;
        $this->product = ManufactureProducts::where('id', $item['product_id'])->first()->toArray();
    }

    function rem_product()
    {
        $this->emitTo('manufacture.jobs.view-job-livewire', 'remove_product', $this->item['id']);
    }

    public function render()
    {
        return view('livewire.manufacture.jobs.item');
    }
}
