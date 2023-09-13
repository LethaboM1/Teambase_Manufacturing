<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureProductTransactions;

class GoodsReceived extends Component
{
    use WithPagination;
    public $tab, $search, $search_arc, $search_receive_goods;

    protected $listeners = [
        'refreshNewDispatch'
    ];

    function mount()
    {
        if (session()->get('tab')) {
            if (in_array(session()->get('tab'), ['receiving', 'archive'])) {
                $this->tab = session()->get('tab');
            }
        } else {
            $this->tab = 'receiving';
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

    function updatedSearchReceiving()
    {
        $this->tab = 'receiving';
    }

    public function render()
    {
        $archive = ManufactureProductTransactions::where('type', 'REC')->where('status', 'Completed')
            ->when($this->search_receive_goods, function ($query, $term) {
                $term = "%{$term}%";
                $query->where('reference_number', 'LIKE', $term)
                    ->orWhere('registration_number', 'LIKE', $term);
            })
            ->paginate(15, ['*'], 'received');


        $receiving = ManufactureProductTransactions::where('type', 'REC')->where('status', 'Pending')
            ->when($this->search_receive_goods, function ($query, $term) {
                $term = "%{$term}%";
                $query->where('reference_number', 'LIKE', $term)
                    ->orWhere('registration_number', 'LIKE', $term);
            })
            ->paginate(15, ['*'], 'received');




        return view('livewire.manufacture.dispatch.goods-received', [
            'archive' => $archive,
            'receiving' => $receiving
        ]);
    }
}
