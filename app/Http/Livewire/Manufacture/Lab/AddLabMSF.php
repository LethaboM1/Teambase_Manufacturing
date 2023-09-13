<?php

namespace App\Http\Livewire\Manufacture\Lab;

use Livewire\Component;

class AddLabMSF extends Component
{
    public $sample, $batch;

    function mount($sample, $batch)
    {
        $this->sample = $sample;
        $this->batch = $batch;
    }

    public function render()
    {
        return view('livewire.manufacture.lab.add-lab-m-s-f');
    }
}
