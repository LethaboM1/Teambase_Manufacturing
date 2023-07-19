<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureProducts;

class ReadyBatchesListLivewire extends Component
{
    use WithPagination;
    public $tab, $search, $search_arc;

    function mount()
    {
    }

    function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $batches_list = ManufactureBatches::where('status', '!=', 'Completed')->where('status', 'Ready for dispatch')->when($this->search, function ($query, $term) {
            $term = "%{$term}%";
            return $query->where('batch_number', 'like', $term)
                ->orWhereIn('product_id', ManufactureProducts::select(DB::raw("id as product_id"))->where('code', 'like', $term))
                ->orWhereIn('product_id', ManufactureProducts::select(DB::raw("id as product_id"))->where('description', 'like', $term));
        })->paginate(15);

        return view('livewire.manufacture.dispatch.ready-batches-list-livewire', [
            'batches_list' => $batches_list
        ]);
    }
}
