<?php

namespace App\Http\Livewire\Manufacture\Batches;

use App\Http\Controllers\DefaultsController;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProductRecipe;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureProducts;

class CreateBatchLivewire extends Component
{
    public $product_id, $jobcards, $qty, $unit, $qty_selected;

    protected $recipe, $listeners = ['select_job'];

    function mount()
    {
        $this->jobcards = [];
        $this->qty_selected = 0;
    }

    function updatedProductId($value)
    {
        if ($this->product_id == 0) {
            $this->jobcards = [];
            $this->qty_selected = 0;
            return;
        }

        $product = ManufactureProducts::where('id', $this->product_id)->first();

        $this->unit = $product->unit_measure;
        $this->jobcards = [];
        $this->qty_selected = 0;
    }

    function select_job($value)
    {
        $key = array_search($value['id'], array_column($this->jobcards, 'id'));
        if ($value['checked']) {
            // dd('checked');
            if (!$key) {
                $job = ManufactureJobcards::where('id', $value['id'])->first();
                $this->jobcards[] = [
                    'id' => $value['id'],
                    'jobcard_number' => $job->jobcard_number,
                    'contractor' => $job->contractor,
                    'qty' => $value['qty'],
                ];
                $this->qty_selected += (float)$value['qty'];
            }
        } else {
            // dd('unchecked');
            if ($key !== false) {
                unset($this->jobcards[$key]);
                $this->qty_selected -= (float)$value['qty'];
            }
        }

        $this->jobcards = array_values($this->jobcards);
    }

    public function render()
    {
        $unit_measure_list = DefaultsController::unit_measure;
        $products_list = ManufactureProducts::select(DB::raw("id as value, concat(code,' - ',description) as name"))->where('has_recipe', 1)->where('active', 1)->get()->toArray();
        array_unshift($products_list, ['name' => '...', 'value' => 0]);

        if ($this->product_id > 0) {
            $jobcard_list = ManufactureJobcardProducts::where('filled', 0)->where('product_id', $this->product_id)->get();
            $this->recipe = ManufactureProductRecipe::where('product_id', $this->product_id)->get();
        } else {
            $jobcard_list = [];
        }

        return view('livewire.manufacture.batches.create-batch-livewire', [
            'products_list' => $products_list,
            'unit_measure_list' => $unit_measure_list,
            'jobcard_list' => $jobcard_list,
            'recipe' => $this->recipe
        ]);
    }
}
