<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcardProductDispatches;

class NewBatchLineLivewire extends Component
{
    public $dispatch, $dispatchaction;

    function mount($dispatch)
    {
        $this->dispatch = $dispatch;
    }


    public function render()
    {

        return view('livewire.manufacture.dispatch.new-batch-line-livewire');
    }
}
