<?php

namespace App\Http\Livewire\Manufacture\Batches;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;

class ListLivewire extends Component
{
    use WithPagination;
    public $tab, $search
    /* , $search_arc Merged into single batch list 2023-10-19  */;

    function mount()
    {
        $this->tab = 'open';
    }

    /* function updatedSearchArc()
    {
        $this->tab = 'archive';
    } Merged into single batch list 2023-10-19  */

    function updatedSearch()
    {
        $this->tab = 'open';
    }

    public function render()
    {
        $batches_list = ManufactureBatches::where('status', '!=', '')->when($this->search, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('batch_number', 'like', $term)
                ->orWhereIn('product_id', ManufactureProducts::select(DB::raw("id as product_id"))->where('code', 'like', $term))
                ->orWhereIn('product_id', ManufactureProducts::select(DB::raw("id as product_id"))->where('description', 'like', $term));
        })->orderBy('created_at', 'desc')->paginate(15, ['*'], 'open');

        /* $archive_list = ManufactureBatches::where('status', 'Completed')->orWhere('status', 'Canceled')->when($this->search_arc, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('batch_number', 'like', $term)
                ->orWhereIn('product_id', ManufactureProducts::select(DB::raw("id as product_id"))->where('code', 'like', $term))
                ->orWhereIn('product_id', ManufactureProducts::select(DB::raw("id as product_id"))->where('description', 'like', $term));
        })->paginate(15, ['*'], 'arc'); Merged into single batch list 2023-10-19 */
        //$batches_list = ManufactureBatches::where('status', '!=', 'Completed')->where('status', '!=', 'Canceled')->toSql();

        return view('livewire.manufacture.batches.list-livewire', [
            'batches_list' => $batches_list
            /*, 'archive_list' => $archive_list  Merged into single batch list 2023-10-19 */
        ]);
    }
}
