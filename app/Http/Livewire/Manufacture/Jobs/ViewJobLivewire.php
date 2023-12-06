<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Models\ManufactureCustomers;
use App\Models\ManufactureJobcardProducts;

class ViewJobLivewire extends Component
{
    use WithPagination;

    public $jobcard, $edit = 0, $unit_measure, $product_list, $product_id, $qty;

    protected $listeners = ['remove_product' => 'rem_product'];

    function mount($job)
    {
        $this->jobcard = ManufactureJobcards::where('id', $job)->first()->toArray();
        unset($this->jobcard['updated_at']);
        unset($this->jobcard['created_at']);

        $this->product_list = ManufactureProducts::select(DB::raw("concat(code,' - ',description) as name, id as value"))->where('active', 1)->orderBy('code', 'asc')->get()->toArray();
        array_unshift($this->product_list, ['name' => '...', 'value' => 0]);

        $this->unit_measure = '';
        $this->qty = 1;
    }

    function rem_product($value)
    {
        ManufactureJobcardProducts::where('id', $value)->delete();
    }

    function updatedJobcard()
    {
        $this->edit = 1;
    }

    function updatedProductId()
    {
        $product = ManufactureProducts::where('id', $this->product_id)->first();
        $this->unit_measure = $product->unit_measure;
    }

    function save_jobcard()
    {
        // dd($this->jobcard);
        ManufactureJobcards::where('id', $this->jobcard['id'])->update($this->jobcard);
        $this->edit = 0;
    }

    // function save_jobcard()
    // {
    //     ManufactureJobcards::where('id', $this->jobcard['id'])->update($this->jobcard);
    //     $this->edit = 0;
    // }

    function add_product()
    {
        if ($this->product_id <= 0) return back()->with('error', 'Choose a product');
        if ($this->qty <= 0)  return back()->with('error', 'Choose a qty');

        $chk = ManufactureJobcardProducts::where('job_id', $this->jobcard['id'])->where('product_id', $this->product_id)->first();

        if (isset($chk) && $chk->count() > 0) {
            $qty = $this->qty +  $chk->qty;
            ManufactureJobcardProducts::where('id', $chk->id)->update([
                'qty' => $qty
            ]);
        } else {
            ManufactureJobcardProducts::insert([
                'job_id' => $this->jobcard['id'],
                'product_id' => $this->product_id,
                'qty' => $this->qty
            ]);
        }

        $this->product_id = 0;
        $this->qty = 1;
    }

    public function render()
    {
        $customer_list = [];
        $customer_list = ManufactureCustomers::select('id as value', DB::raw("name"))
            ->get()
            ->toArray();


        if (count($customer_list) > 0) {
            array_unshift($customer_list, ['value' => 0, 'name' => 'Select Customer']);
        } else {
            $customer_list = [];
            array_unshift($customer_list, ['value' => 0, 'name' => 'No Customers found...']);
        }

        $products = ManufactureJobcardProducts::where('job_id', $this->jobcard['id'])->paginate(15);

        return view('livewire.manufacture.jobs.view-job-livewire', [
            'products' => $products,
            'customer_list' => $customer_list
        ]);
    }
}
