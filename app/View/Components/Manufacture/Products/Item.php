<?php

namespace App\View\Components\Manufacture\Products;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureProducts;
use App\Http\Controllers\DefaultsController;
use App\Models\ManufactureProductTransactions;

class Item extends Component
{
    public $item, $unit_measure_list, $product_list, $history;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
        $this->product_list = ManufactureProducts::select(DB::raw("concat(code,'-',description) as name, id as value"))->where('id', '!=', $item->id)->orderBy('code')->get();
        $this->unit_measure_list = DefaultsController::unit_measure;
        $this->history = ManufactureProductTransactions::where('product_id', $this->item['id'])->orderBy('created_at', 'desc')->limit(5)->get();
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
