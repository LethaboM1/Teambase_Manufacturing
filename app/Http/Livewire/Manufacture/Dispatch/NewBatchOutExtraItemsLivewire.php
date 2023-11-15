<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;

class NewBatchOutExtraItemsLivewire extends Component
{
    public $extraitem, $dispatchaction;
    

    public function mount($extraitem, $dispatchaction)
    {
        $this->extraitem = $extraitem;        
        $this->dispatchaction = $dispatchaction;
    }

    public function removeExtraItem ($key){
        $this->emitUp('removeExtraItem', $key);
    }

    public function render()
    {
        return view('livewire.manufacture.dispatch.new-batch-out-extra-items-livewire');
    }
}
