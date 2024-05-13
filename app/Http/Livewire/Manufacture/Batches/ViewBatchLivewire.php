<?php

namespace App\Http\Livewire\Manufacture\Batches;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureProducts;
use App\Http\Controllers\SelectLists;
use Illuminate\Support\Facades\Session;
use App\Models\ManufactureProductRecipe;
use App\Models\ManufactureJobcardProducts;

class ViewBatchLivewire extends Component
{
    use WithPagination;

    public $batch, $batch_product, $recipe, $status_list, $status, $notes, $changed = 0, $qty;

    function mount($batch)
    {
        $this->batch = ManufactureBatches::where('id', $batch)->first();

        $this->notes = $this->batch->notes;
        $this->status = $this->batch->status;
        $this->status_list = SelectLists::batch_status_list;
        $this->qty = $this->batch->qty;
    }

    function updatedNotes()
    {
        // ManufactureBatches::where('id', $this->batch->id)->update(['notes' => $this->notes]); 2024-05-09 Moved to a Save Click instead
        $this->changed = 1;
    }

    function updatedStatus()
    {        
        // ManufactureBatches::where('id', $this->batch->id)->update(['status' => $this->status]); 2024-05-09 Moved to a Save Click instead
        $this->changed = 1;
    }

    function updatedQty()
    {
        // ManufactureBatches::where('id', $this->batch->id)->update(['qty' => $this->qty]); 2024-05-09 Moved to a Save Click instead
        $this->changed = 1;
    }

    function save_batch()
    {        
        if ($this->qty <= 0) return back()->with('alertError', 'Quantity cannot be Zero.'); 

        $form_fields = ['notes' => $this->notes, 
                        'status' => $this->status,
                        'qty' => $this->qty 
        ];

        if (ManufactureBatches::where('id', $this->batch->id)->update($form_fields)){
            $this->changed = 0;
            return back()->with('alertMessage', 'Changes Saved!');
        } else {
            $this->changed = 1;            
            return back()->with('alertError', 'Changes could not be saved.');
        }        
    }

    public function render()
    {
        $this->recipe = ManufactureProductRecipe::where('product_id', $this->batch->product_id)->get();
        $this->batch_product = ManufactureProducts::select(DB::raw("concat(code,' - ',description) as name, unit_measure "))->where('id', $this->batch->product_id)->first();
        return view('livewire.manufacture.batches.view-batch-livewire', [
            'recipe' => $this->recipe,
            'qty' => $this->qty
        ]);
    }
}
