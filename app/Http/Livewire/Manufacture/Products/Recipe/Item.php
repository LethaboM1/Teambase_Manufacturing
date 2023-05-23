<?php

namespace App\Http\Livewire\Manufacture\Products\Recipe;

use App\Models\ManufactureProductRecipe;
use Livewire\Component;
use App\Models\ManufactureProducts;

class Item extends Component
{
    public $item, $item_id, $product, $qty, $delete = 0;

    function mount($item)
    {
        $this->item = $item;
        $this->item_id = $item['id'];
        $this->qty = $item['qty'];
    }

    function updatedQty($value)
    {
        if (is_numeric($value)) {
            ManufactureProductRecipe::where('id', $this->item_id)->update([
                'qty' => $value
            ]);
        }
    }

    public function render()
    {
        $this->product = ManufactureProducts::select('code', 'description', 'unit_measure')->where('id', $this->item['product_add_id'])->first();
        return view('livewire.manufacture.products.recipe.item');
    }
}
