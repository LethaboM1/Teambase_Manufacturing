<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Models\ManufactureSuppliers;
use Livewire\Component;

class ReceiveGoodsLivewire extends Component
{
    public function render()
    {
        $supplier_list = ManufactureSuppliers::select('id as value', 'name')->get();
        return view('livewire.manufacture.dispatch.receive-goods-livewire', [
            'supplier_list' => $supplier_list
        ]);
    }
}
