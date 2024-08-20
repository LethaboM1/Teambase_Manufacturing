<?php

namespace App\View\Components\Manufacture\Products;

use App\Models\Approvals;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureProducts;
use App\Http\Controllers\SelectLists;
use App\Http\Controllers\DefaultsController;
use App\Models\ManufactureProductTransactions;

class Item extends Component
{
    public $item, $unit_measure_list, $product_list, $history, $lab_test_list, $approval_request='false', $approval, $approval_post, $approval_detail;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;        
        $this->product_list = ManufactureProducts::select(DB::raw("concat(code,'-',description) as name, id as value"))->where('id', '!=', $item->id)->orderBy('code')->get();
        $this->unit_measure_list = DefaultsController::unit_measure;
        $this->history = ManufactureProductTransactions::where('product_id', $this->item['id'])->orderBy('created_at', 'desc')->limit(5)->get();
        $this->lab_test_list = SelectLists::labs;
        $this->approval=Approvals::where('request_type', 'Stock Adjustment')->where('request_model', 'manufacture_products')->where('request_model_id',  $item->id)->where('approved','0')->where('declined','0')->first();
        if($this->approval!=null){
            
            if(count($this->approval->toArray())>0){
                //We have a pending Approval Request
                $this->approval_request='true';
                $this->approval_post=base64_encode(json_encode($this->approval));
                // dd($this->approval);
                $this->approval_detail = base64_decode($this->approval['request_detail_array']);
                $this->approval_detail = json_decode($this->approval_detail, true);                
                $this->approval['request']=$this->approval_detail;
                $this->approval_detail = $this->approval['request_detail_array'];
                unset($this->approval['request_detail_array']);
            }

        } else $this->approval_request='false';

        // dd($this->approval);

        array_unshift($this->lab_test_list, ['name' => 'Select One', 'value' => 0]);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.manufacture.products.item');
    }
}
