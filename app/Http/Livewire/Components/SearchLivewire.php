<?php

namespace App\Http\Livewire\Components;

use App\Models\Plants;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureCustomers;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureProducts;

class SearchLivewire extends Component
{
    public $name, $label, $hide, $list, $list_value, $list_name, $value, $search, $search_name, $job_id;

    function mount($name, $label = '', $hide = true, $value = '', $jobid = 0)
    {
        $this->name = $name;
        $this->label = $label;
        $this->hide = $hide;
        $this->job_id = $jobid;
        $this->list = [];
        $this->value = $value;
    }

    function updatedValue()
    {
        $this->emitUp("emitSet", "{$this->name}", $this->value);
        $this->search = '';
    }

    public function render()
    {
        $this->list = [];
        switch ($this->name) {
            case 'plant_id':
                $this->list = Plants::select('plant_id as value', DB::raw("concat(plant_number,' - ',IFNULL(make,''),' ',IFNULL(model,'')) as name"))->when($this->search, function ($query) {
                    $query->search($this->search);
                })->get();

                if ($this->value > 0) {
                    $result = Plants::where('plant_id', $this->value)->first();
                    $this->search_name = "{$result['plant_number']} - {$result['make']} {$result['model']}";
                }

                break;


            case 'customer_id':
                $this->list = ManufactureCustomers::select('id as value', DB::raw("concat(account_number,' - ',name) as name"))->when($this->search, function ($query) {
                    $query->search($this->search);
                })->get();

                if ($this->value > 0) {
                    $result = ManufactureCustomers::where('id', $this->value)->first();
                    $this->search_name = "{$result['account_number']} - {$result['name']}";
                }
                break;

            case 'product_id':
                $this->list = ManufactureProducts::select('id as value', DB::raw("concat(code,' - ',description) as name"))
                    ->where(function ($query) {
                        $query->where('weighed_product', 1)
                            ->orWhere('has_recipe', 1);
                    })
                    ->when($this->search, function ($query) {
                        $query->search($this->search);
                    })->get();

                if ($this->value > 0) {
                    $result = ManufactureProducts::where('id', $this->value)->first();
                    $this->search_name = "{$result['code']} - {$result['description']}";
                }
                break;


            case 'job_id'/*  || 'transfer_job_id' */:
                $this->list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' - ',IFNULL(contractor,'')) as name"))
                    ->where('status', 'Open')
                    ->when($this->search, function ($query) {
                        $query->search($this->search);
                    })->get();                    

                if ($this->value > 0) {
                    $result = ManufactureJobcards::where('id', $this->value)->first();                   
                    
                    $this->search_name = "{$result['jobcard_number']} - {$result['contractor']}";                    
                }
                
                break;

            case 'transfer_job_id':
                $this->list = ManufactureJobcards::select('id as value', DB::raw("concat(jobcard_number,' - ',IFNULL(contractor,'')) as name"))
                    ->where('status', 'Open')
                    ->when($this->search, function ($query) {
                        $query->search($this->search);
                    })->get();                    

                if ($this->value > 0) {
                    $result = ManufactureJobcards::where('id', $this->value)->first();
                    if(!$result){
                        dd('value:'.$this->value.' result:'.$result);
                    }
                    
                    $this->search_name = "{$result['jobcard_number']} - {$result['contractor']}";                    
                }
                
                break;

            case 'manufacture_jobcard_product_id':
                if ($this->job_id > 0) {
                    $products_list = ManufactureJobcardProducts::select('id', 'product_id')
                        ->where('job_id', $this->job_id)
                        ->when($this->search, function ($query) {
                            $query->search($this->search);
                        })->get();
                    // dd($products_list);

                    foreach ($products_list as $product) {
                        $product_ = ManufactureProducts::select('code', 'description', 'weighed_product', 'has_recipe')->where('id', $product['product_id']);

                        if ($product_->count() !== 0) {

                            if ($product_->first()['weighed_product'] == 1 || $product_->first()['has_recipe'] == 1) {
                                $this->list[] = [
                                    'value' => $product['id'],
                                    'name' => $product_->first()['code'] . ' - ' . $product_->first()['description']
                                ];
                            }
                        }
                    }

                    if ($this->value > 0) {
                        $result = ManufactureJobcardProducts::where('id', $this->value)->first();
                        $this->search_name = "{$result->product()['code']} - {$result->product()['description']}";
                    }
                }

                break;
        }

        return view('livewire.components.search-livewire');
    }
}
