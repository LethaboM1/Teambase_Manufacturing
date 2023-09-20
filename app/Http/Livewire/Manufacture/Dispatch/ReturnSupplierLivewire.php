<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureProducts;
use App\Models\ManufactureSuppliers;
use App\Http\Controllers\SelectLists;

class ReturnSupplierLivewire extends Component
{
    public function render()
    {
        $supplier_list = ManufactureSuppliers::select('id as value', 'name')->get()->toArray();
        array_unshift($supplier_list, SelectLists::empty_select);

        $products_list = ManufactureProducts::select("id as value", DB::raw("concat(code,' - ',description) as name"))->where('has_recipe', 0)->get()->toArray();
        array_unshift($products_list, SelectLists::empty_select);

        return view('livewire.manufacture.dispatch.return-supplier-livewire', [
            'supplier_list' => $supplier_list,
            'products_list' => $products_list,
        ]);
    }
}
