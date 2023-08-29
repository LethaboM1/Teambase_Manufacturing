<?php

namespace App\Http\Livewire\Manufacture\Suppliers;

use Livewire\Component;

class ListItemLivewire extends Component
{
    public $supplier;
    function mount($supplier)
    {
        $this->supplier = $supplier;
    }
    public function render()
    {
        return view('livewire.manufacture.suppliers.list-item-livewire');
    }
}
