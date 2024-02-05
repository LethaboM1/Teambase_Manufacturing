<?php

namespace App\Http\Livewire\Manufacture\Suppliers;

use App\Models\ManufactureSuppliers;
use Livewire\Component;
use Livewire\WithPagination;

class ViewLivewire extends Component
{
    use WithPagination;
    public $search;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $suppliers = ManufactureSuppliers::when($this->search, function ($query, $search) {
            $search = "%{$search}%";
            $query->where('name', 'like', $search)
                ->orWhere('contact_name', 'like', $search)
                ->orWhere('contact_number', 'like', $search)
                ->orWhere('email', 'like', $search)
                ->orWhere('address', 'like', $search);
        })
            ->orderBy('name')
            ->paginate(25);

        return view('livewire.manufacture.suppliers.view-livewire', [
            'suppliers' => $suppliers
        ]);
    }
}
