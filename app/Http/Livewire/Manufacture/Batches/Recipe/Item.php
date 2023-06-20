<?php

namespace App\Http\Livewire\Manufacture\Batches\Recipe;

use Livewire\Component;

class Item extends Component
{
    public $product, $qty, $in_stock, $flag;

    function mount($product, $qtyselected)
    {
        $this->product = $product;
        $this->qty = ($qtyselected > 0 ? $product->qty * $qtyselected : 0);
        $this->in_stock = $product->product()->qty;

        $this->flag = ($this->qty > $this->in_stock) ? ($this->qty - $this->in_stock) : 0;
    }

    public function render()
    {
        return view('livewire.manufacture.batches.recipe.item');
    }
}
