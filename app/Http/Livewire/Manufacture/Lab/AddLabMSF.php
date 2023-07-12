<?php

namespace App\Http\Livewire\Manufacture\Lab;

use Livewire\Component;

class AddLabMSF extends Component
{
    public $sammple, $batch;

    function mount($sammple, $batch)
    {
        $this->sammple = $sammple;
        $this->batch = $batch;
    }

    public function render()
    {
        return view('livewire.manufacture.lab.add-lab-m-s-f');
    }
}
