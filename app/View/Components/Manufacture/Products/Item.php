<?php

namespace App\View\Components\Manufacture\Products;

use App\Http\Controllers\DefaultsController;
use Illuminate\View\Component;

class Item extends Component
{
    public $item, $unit_measure_list;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
        $this->unit_measure_list = DefaultsController::unit_measure;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.manufacture.products.item');
    }
}
