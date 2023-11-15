<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;


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
