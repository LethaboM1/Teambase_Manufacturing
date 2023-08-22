<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureJobcardProductDispatches;

class NewBatchesLivewire extends Component
{
    use WithPagination;
    public $search, $search_arc;

    protected $listeners = [
        'refreshNewDispatch' => '$refresh'
    ];

    function mount()
    {
    }

    function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $dispatches = ManufactureJobcardProductDispatches::where('status', 'Loading')->paginate(15);

        return view('livewire.manufacture.dispatch.new-batches-livewire', [
            'dispatches' => $dispatches
        ]);
    }
}
