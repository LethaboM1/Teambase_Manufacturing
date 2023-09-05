<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcards;

class NewBatchesLivewire extends Component
{
    use WithPagination;
    public $tab, $search, $search_arc;

    protected $listeners = [
        'refreshNewDispatch'
    ];

    function mount()
    {
        $this->tab = 'loading';
    }

    /* function updatedSearch()
    {
        $this->resetPage();
    } */
    //2023-09-01 - removed to allow for Tab Navs

    function refreshNewDispatch()
    {        
        $this->resetPage();
    }

    function updatedSearchArc()
    {
        $this->tab = 'archive';
    }

    function updatedSearch()
    {
        $this->tab = 'loading';
    }

    public function render()
    {
                
        $dispatches = ManufactureJobcardProductDispatches::where('status', 'Loading')
        ->when($this->search, function ($query, $term) {
            
            $term = "%{$term}%";
            $query->where('dispatch_number', 'like', $term)
                ->orWhere('reference', 'like', $term)
                /* ->orWhereIn('manufacture_jobcard_product_id', ManufactureJobcardProducts::select(['id'])
                            ->whereIn('jobcard_id', ManufactureJobcards::select(['id'])
                                                ->where('jobcard_number', 'like', $term))) */
                ->orWhere('haulier_code', 'like', $term);
        })->paginate(15, ['*'], 'loading');


        $dispatches_archived = ManufactureJobcardProductDispatches::where('status', '!=', 'Loading')
        ->when($this->search_arc, function ($query, $term) {
            
            $term = "%{$term}%";
            $query->where('dispatch_number', 'like', $term)
                ->orWhere('reference', 'like', $term)
                /* ->orWhere('manufacture_jobcard_product_id', ManufactureJobcardProducts::select(['id'])
                                            ->whereIn('jobcard_id', ManufactureJobcards::select(['id'])
                                                ->whereIn('jobcard_number', 'like', $term))) */
                ->orWhere('haulier_code', 'like', $term);
        })->paginate(15, ['*'], 'archived');

        
        return view('livewire.manufacture.dispatch.new-batches-livewire', [
            'dispatches' => $dispatches,
            'dispatches_archived' => $dispatches_archived
        ]);
    }
}
