<?php

namespace App\Http\Livewire\Manufacture\Customers;

use App\Models\ManufactureCustomers;
use Livewire\Component;

class ViewLivewire extends Component
{
    public $search;

    public function render()
    {
        $customers = ManufactureCustomers::when($this->search, function ($query, $search) {
            $search = "%{$search}%";
            $query->where('name', 'like', $search)
                ->orWhere('account_number', 'like', $search)
                ->orWhere('contact_name', 'like', $search)
                ->orWhere('contact_number', 'like', $search)
                ->orWhere('email', 'like', $search)
                ->orWhere('address', 'like', $search);
        })
            ->orderBy('name')
            ->paginate(25);

        return view('livewire.manufacture.customers.view-livewire', [
            'customers' => $customers
        ]);
    }
}
