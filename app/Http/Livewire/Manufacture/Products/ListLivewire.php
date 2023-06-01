<?php

namespace App\Http\Livewire\Manufacture\Products;

use App\Http\Controllers\DefaultsController;
use App\Models\ManufactureProducts;
use Livewire\Component;
use Livewire\WithPagination;

class ListLivewire extends Component
{
    use WithPagination;

    public $search, $unit_measure_list;
    protected $paginationTheme = 'bootstrap';

    function mount()
    {
        $this->unit_measure_list = DefaultsController::unit_measure;
    }

    public function render()
    {
        $products_list = ManufactureProducts::where('active', 1)->when($this->search, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('code', 'like', $term)
                ->orWhere('description', 'like', $term);
        })->orderBy('code')->paginate(15);

        return view('livewire.manufacture.products.list-livewire', [
            'products_list' => $products_list
        ]);
    }
}
