<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProductTransactions;

class NewBatchesLivewire extends Component
{
    use WithPagination;
    public $tab, $search, $search_arc, $search_receive_goods;

    protected $listeners = [
        'refreshNewDispatch'
    ];

    function mount()
    {
        if (session()->get('tab')) {
            if (in_array(session()->get('tab'), ['loading', 'archive'])) {
                $this->tab = session()->get('tab');
            }
        } else {
            $this->tab = 'loading';
        }
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
                    ->orWhere('haulier_code', 'like', $term);
            })->paginate(15, ['*'], 'loading');

        /* $product_transactions = ManufactureProductTransactions::where('type', 'REC')->where('status', 'Pending')
            ->when($this->search_receive_goods, function ($query, $term) {
                $term = "%{$term}%";
                $query->where('reference_number', 'LIKE', $term)
                    ->orWhere('registration_number', 'LIKE', $term);
            })
            ->paginate(15, ['*'], 'received'); */ //Moved to Receiving List

        $dispatches_archived = ManufactureJobcardProductDispatches::where('status', '!=', 'Loading')
            ->when($this->search_arc, function ($query, $term) {

                $term = "%{$term}%";
                $query->where('dispatch_number', 'like', $term)
                    ->orWhere('reference', 'like', $term)                    
                    ->orWhere('haulier_code', 'like', $term);
            })->paginate(15, ['*'], 'archived');


        return view('livewire.manufacture.dispatch.new-batches-livewire', [
            'dispatches' => $dispatches,
            'dispatches_archived' => $dispatches_archived,
            /* 'product_transactions' => $product_transactions */ //Moved to Receiving List
        ]);
    }
}
