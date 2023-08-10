<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureJobcardProductDispatches;

class NewBatchesLivewire extends Component
{
    use WithPagination;
    public $search, $search_arc;

    function mount()
    {
    }

    function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $dispatches = ManufactureJobcardProductDispatches::where('status', 'New')->paginate(15);

        return view('livewire.manufacture.dispatch.new-batches-livewire', [
            'dispatches' => $dispatches
        ]);
    }
}
