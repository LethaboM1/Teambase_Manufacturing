<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureProductTransactions;

class ReturnBatchModal extends Component
{
    /* public $dispatch, $weight_in, $dispatchaction, $extraitems, $customer_dispatch;

    function mount($dispatch, $dispatchaction)
    {
        $this->dispatch = $dispatch;
        $this->dispatchaction = $dispatchaction;

        if ($dispatch->customer_id == '0') {
            $this->customer_dispatch = 0;
        } else {
            $this->customer_dispatch = 1;
        }
    }

    public function render()
    {   
        
        $extra_items = [];
        $extra_items = ManufactureProductTransactions::select(
            'id',
            'dispatch_id',
            'weight_in_datetime as the_date',
            'weight_out',
            DB::raw('(select description from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as the_description'),
            DB::raw('(select unit_measure from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as the_unit'),
            'qty as the_qty'
        )
            ->where('dispatch_id', $this->dispatch->id)
            ->get()
            ->toArray();

        return view('livewire.manufacture.dispatch.return-batch-modal', [
            'customer_dispatch' => $this->customer_dispatch,
            'extra_items' => $extra_items,
        ]); 
    } */ /* Obsolete - Returns / Transfer on Batch Out Modal on line item level 2023-12-05 */
}
