<?php

namespace App\Http\Livewire\Manufacture\Batches;

use App\Http\Controllers\SelectLists;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureProducts;
use App\Models\ManufactureProductRecipe;
use App\Models\ManufactureJobcardProducts;

class ViewBatchLivewire extends Component
{
    use WithPagination;

    public $batch, $batch_product, $recipe, $status_list, $status, $notes, $saved = 0;

    function mount($batch)
    {
        $this->batch = ManufactureBatches::where('id', $batch)->first();

        $this->notes = $this->batch->notes;
        $this->status = $this->batch->status;
        $this->status_list = SelectLists::batch_status_list;
    }

    function updatedNotes()
    {
        ManufactureBatches::where('id', $this->batch->id)->update(['notes' => $this->notes]);
    }

    function updatedStatus()
    {
        ManufactureBatches::where('id', $this->batch->id)->update(['status' => $this->status]);
    }

    public function render()
    {
        $this->recipe = ManufactureProductRecipe::where('product_id', $this->batch->product_id)->get();
        $this->batch_product = ManufactureProducts::select(DB::raw("concat(code,' - ',description) as name, unit_measure "))->where('id', $this->batch->product_id)->first();
        return view('livewire.manufacture.batches.view-batch-livewire', [
            'recipe' => $this->recipe
        ]);
    }
}
