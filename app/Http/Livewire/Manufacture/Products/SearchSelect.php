<?php

namespace App\Http\Livewire\Manufacture\Products;

use App\Models\ManufactureProducts;
use Livewire\Component;

class SearchSelect extends Component
{
    public $search, $products, $product_id, $product;

    function mount()
    {
        $this->search = '';
        $this->products = [];
        $this->product = [];
    }

    function updatedProductId()
    {
        $this->product = ManufactureProducts::where('id', $this->product_id)->first()->toArray();
    }

    function updatedSearch()
    {
        $term = "%{$this->search}%";
        $this->products = ManufactureProducts::where('code', 'like', $term)->orWhere('description', 'like', $term)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.manufacture.products.search-select');
    }
}
