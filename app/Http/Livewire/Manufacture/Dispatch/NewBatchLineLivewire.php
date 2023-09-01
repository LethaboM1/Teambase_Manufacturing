<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use Livewire\Component;

class NewBatchLineLivewire extends Component
{
    public $dispatch, $weight_out, $weight_out_datetime, $qty;

    function mount($dispatch)
    {
        $this->dispatch = $dispatch;
        $this->weight_out_datetime = date("Y-m-d\TH:i");
        $this->qty = 0;
    }

    function dispatch_out()
    {
        $error = false;
        // dd("Ola!");
        if ($this->qty == 0) {
            $error = true;
            session()->flash('dispatch_error', 'Qty is zero');
        }

        if (!Functions::validDate($this->weight_out_datetime, "Y-m-d\TH:i")) {
            $error = true;
            session()->flash('dispatch_error', 'Invalid date time');
        }

        $product_qty = $this->dispatch->jobcard_product()->qty_due;

        if ($product_qty < $this->qty) {
            $error = true;
            session()->flash('dispatch_error', "Too much product. Due amount on this job card is {$product_qty}");
        }

        if (!$error) {
            $form_fields = [
                'weight_out' => $this->weight_out,
                'weight_out_datetime' => $this->weight_out_datetime,
                'weight_out_user_id' => auth()->user()->user_id,
                'qty' => $this->qty,
                'status' => 'Dispatched'
            ];

            ManufactureJobcardProductDispatches::where('id', $this->dispatch->id)->update($form_fields);

            if ($product_qty == $this->qty) {
                ManufactureJobcardProducts::where('id', $this->dispatch->jobcard_product()->id)->update(['filled' => 1]);
            }

            $this->emit('refreshNewDispatch');

            if ($this->dispatch->jobcard_product()->product()->has_recipe == 0) {
                /* Adjust transaction if no recipe  */
            }

            /* Close job card if filled  */

            /* Connie */

            // dd($form_fields);
        }
    }

    function updatedWeightOut($value)
    {
        if ($value < $this->dispatch->weight_in) return;
        $this->qty = $value - $this->dispatch->weight_in;
    }

    public function render()
    {
        return view('livewire.manufacture.dispatch.new-batch-line-livewire');
    }
}
