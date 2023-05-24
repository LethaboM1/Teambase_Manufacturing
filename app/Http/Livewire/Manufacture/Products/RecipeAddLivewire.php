<?php

namespace App\Http\Livewire\Manufacture\Products;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureProducts;
use App\Models\ManufactureProductRecipe;
use App\Http\Controllers\DefaultsController;

class RecipeAddLivewire extends Component
{
    public  $item, $product_list, $unit_measure_list, $unit_measure, $delete;

    public  $product,
        $qty;

    protected $rules = [
            'product' => 'required',
            'qty' => 'required|gt:0'
        ],
        $listeners = ['deleteRecipe'];

    function mount($item)
    {
        $this->item = $item;
        $this->unit_measure_list = DefaultsController::unit_measure;
        $this->delete = 0;
    }

    function deleteRecipe($value)
    {
        ManufactureProductRecipe::where('id', $value)->delete();
        $this->delete = null;
    }

    function updatedProduct($value)
    {
        if (strlen($this->product) > 0) {
            $product = ManufactureProducts::select('unit_measure')->where('id', $value)->first();
            $this->unit_measure = $product->unit_measure;
            // dd($this->unit_measure);
        }
    }

    function submit()
    {
        $form_fields = $this->validate();

        $form_fields['product_id'] = $this->item['id'];
        $form_fields['product_add_id'] = $form_fields['product'];
        unset($form_fields['product']);

        ManufactureProductRecipe::insert($form_fields);

        $this->product = '';
        $this->qty = '';

        // dd($form_fields);
    }

    public function render()
    {
        $this->product_list = ManufactureProducts::select(DB::raw("concat(code,' - ',description) as name, id as value"))
            ->where('id', '!=', $this->item['id'])
            ->whereNotIn('id', ManufactureProductRecipe::select(DB::raw("product_add_id as id"))->where('product_id', $this->item['id']))
            ->orderBy('code')->get()->toArray();

        array_unshift($this->product_list, ['name' => '...', 'value' => '']);


        $recipe_items = ManufactureProductRecipe::where('product_id', $this->item['id'])->get()->toArray();


        return view('livewire.manufacture.products.recipe-add-livewire', [
            'recipe_items' => $recipe_items
        ]);
    }
}
