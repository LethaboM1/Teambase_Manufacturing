<?php

namespace App\View\Components\Manufacture\Batches;

use App\Models\ManufactureProducts;
use Illuminate\View\Component;

class Item extends Component
{
    public $batch, $product;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($batch)
    {
        $this->batch = $batch;
        $product = ManufactureProducts::select('code', 'description')->where('id', $this->batch['product_id'])->first();
        if (isset($product)) $this->product = "{$product->code} - {$product->description}";
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.manufacture.batches.item');
    }
}
