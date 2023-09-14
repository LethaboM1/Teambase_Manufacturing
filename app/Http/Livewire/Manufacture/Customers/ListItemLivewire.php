<?php

namespace App\Http\Livewire\Manufacture\Customers;

use Livewire\Component;

class ListItemLivewire extends Component
{
    public $customer;
    function mount($customer)
    {
        $this->customer = $customer;
    }
    public function render()
    {
        return view('livewire.manufacture.customers.list-item-livewire');
    }
}
