<?php

namespace App\Http\Livewire\Manufacture\Lab;

use App\Models\ManufactureBatchLabs;
use App\Models\ManufactureProducts;
use Livewire\Component;
use Livewire\WithPagination;

class ViewBatchLivewire extends Component
{
    use WithPagination;

    public $batch, $product;
    protected $paginationTheme = 'bootstrap';

    function mount($batch)
    {
        $this->batch = $batch;
        $this->product = ManufactureProducts::where('id', $this->batch->product_id)->first();
    }

    public function render()
    {
        $labs =  ManufactureBatchLabs::where('batch_id', $this->batch->id)->paginate(15);
        return view('livewire.manufacture.lab.view-batch-livewire', [
            'labs' => $labs,
        ]);
    }
}
