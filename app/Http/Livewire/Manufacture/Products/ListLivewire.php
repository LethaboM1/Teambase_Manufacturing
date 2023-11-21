<?php

namespace App\Http\Livewire\Manufacture\Products;

use App\Http\Controllers\DefaultsController;
use App\Http\Controllers\Functions;
use App\Models\ManufactureProducts;
use Livewire\Component;
use Livewire\WithPagination;

class ListLivewire extends Component
{
    use WithPagination;

    public $search, $search_raw, $search_recipe, $unit_measure_list, $tab;
    protected $paginationTheme = 'bootstrap';

    function mount()
    {
        $this->unit_measure_list = DefaultsController::unit_measure;
        $this->tab = 'all';
    }

    function fix_items()
    {
        $message = Functions::fix_weighed_items();

        if (strlen($message) > 0) {
            session()->flash('alertMessage', $message);
        } else {
            session()->flash('alertMessage', 'Nothing to fix.');
        }
    }

    function updatingSearch()
    {
        $this->search_raw = '';
        $this->search_recipe = '';
    }

    function updatingSearchRaw()
    {
        $this->search = '';
        $this->search_recipe = '';
    }

    function updatingSearchRecipe()
    {
        $this->search_raw = '';
        $this->search = '';
    }

    public function render()
    {
        $products_list = ManufactureProducts::where('active', 1)->when($this->search, function ($query, $term) {
            $this->tab = 'all';

            $query->where(function ($query) {
                $search = "%{$this->search}%";
                $query->where('code', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        })->orderBy('code')->paginate(15, ['*'], 'pgall');

        $products_raw_list = ManufactureProducts::where('active', 1)->where('has_recipe', 0)->when($this->search_raw, function ($query) {
            $this->tab = 'raw';
            $query->where(function ($query) {
                $search = "%{$this->search_raw}%";
                $query->where('code', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        })->orderBy('code')->paginate(15, ['*'], 'pgraw');


        $products_recipe_list = ManufactureProducts::where('active', 1)->where('has_recipe', 1)->when($this->search_recipe, function ($query) {
            $this->tab = 'recipe';

            $query->where(function ($query) {
                $search = "%{$this->search_recipe}%";
                $query->where('code', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        })->orderBy('code')->paginate(15, ['*'], 'pgrecipe');

        return view('livewire.manufacture.products.list-livewire', [
            'products_list' => $products_list,
            'products_raw_list' => $products_raw_list,
            'products_recipe_list' => $products_recipe_list
        ]);
    }
}
