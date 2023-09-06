<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Http\Controllers\SelectLists;
use App\Models\ManufactureProducts;
use App\Models\ManufactureSuppliers;
use Livewire\Component;

class ReceiveGoodsLivewire extends Component
{
    public function render()
    {
        $supplier_list = ManufactureSuppliers::select('id as value', 'name')->get()->toArray();
        array_unshift($supplier_list, SelectLists::empty_select);

        $products_list = ManufactureProducts::select("id as value", "concat(code,' - ',description) as name")->where('has_recipe', 0)->get();
        return view('livewire.manufacture.dispatch.receive-goods-livewire', [
            'supplier_list' => $supplier_list,
            'products_list' => $products_list,
        ]);
    }
}
