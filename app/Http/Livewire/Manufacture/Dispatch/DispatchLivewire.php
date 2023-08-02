<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Http\Controllers\Functions;
use App\Models\ManufactureBatches;
use App\Models\ManufactureJobcardProductDispatches;
use Livewire\Component;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureProducts;
use Livewire\WithPagination;

class DispatchLivewire extends Component
{
    use WithPagination;

    public $batch, $qty_left, $jobcards;

    protected $listeners = ['checkProduct', 'changeProduct'], $paginateTheme = 'bootstrap';

    function mount($batch)
    {
        $this->batch = $batch;
        $this->qty_left = $batch->qty_left;
        $this->jobcards = [];
    }

    function dispatch()
    {
        if (isset($this->jobcards) && count($this->jobcards) > 0) {
            foreach ($this->jobcards as $key => $jobcard) {
                if ($jobcard['qty'] > 0) {
                    $form_fields = [
                        'dispatch_number' => Functions::get_doc_number('dispatch'),
                        'manufacture_jobcard_product_id' => $key,
                        'batch_id' => $this->batch->id,
                        'qty' => $jobcard['qty']
                    ];

                    ManufactureJobcardProductDispatches::insert($form_fields);
                    $job = ManufactureJobcardProducts::where('id', $key)->first();
                    if ($job->qty_due == 0) ManufactureProducts::where('id', $key)->update(['filled' => 1]);
                }
            }
        }

        $this->batch = ManufactureBatches::where('id', $this->batch->id)->first();
        if ($this->batch->qty_due == 0)  ManufactureBatches::where('id', $this->batch->id)->update(['status' => 'Completed']);
    }

    function changeProduct($id, $from, $to)
    {
        $this->qty_left += $from;

        if ($to > $this->qty_left) {
            $this->emit("setQty:{$id}", $this->qty_left);
            $this->jobcards[$id]['qty'] = $this->qty_left;
            $this->qty_left = 0;
        } else {
            $this->qty_left -= $to;
            $this->jobcards[$id]['qty'] = $to;
        }
    }

    function checkProduct($id, $checked, $qty)
    {

        if ($checked) {
            if ($qty > $this->qty_left) {
                $this->emit("setQty:{$id}", $this->qty_left);
                $this->jobcards[$id]['qty'] = $this->qty_left;
                $this->qty_left = 0;
            } else {
                $this->qty_left -= $qty;
                $this->jobcards[$id]['qty'] = $qty;
            }
        } else {
            $this->qty_left += $qty;
            unset($this->jobcards[$id]);
            if ($this->qty_left > $this->batch->qty) $this->qty_left = $this->batch->qty;
        }


        // $this->emit('updateItems');
    }


    public function render()
    {
        $this->batch = ManufactureBatches::where('id', $this->batch->id)->first();
        if ($this->batch->qty_left > 0) {
            $jobcard_list = ManufactureJobcardProducts::where('filled', 0)->where('product_id', $this->batch->product_id)->paginate(15);
        } else {
            $jobcard_list = [];
        }

        $dispatched = ManufactureJobcardProductDispatches::where('batch_id', $this->batch->id)->paginate(15);

        return view('livewire.manufacture.dispatch.dispatch-livewire', [
            'jobcard_list' => $jobcard_list,
            'dispatched' => $dispatched
        ]);
    }
}
