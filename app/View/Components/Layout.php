<?php

namespace App\View\Components;

use App\Models\Approvals;
use Illuminate\View\Component;
use App\Http\Controllers\Functions;
use App\Models\ManufactureProducts;
use Illuminate\Support\Facades\Auth;
use App\Models\ManufactureProductTransactions;
use App\Models\ManufactureJobcardProductDispatches;

class Layout extends Component
{
    public $page_title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($pageTitle)
    {
        // $this->user_role = $userRole;
        $this->page_title = $pageTitle;
        // $this->department = $department;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $transfer_requests = Approvals::where('request_type', 'Dispatch Transfer')->where('approved','0')->where('declined','0')->orderBy('created_at','asc')->get()->toArray();        
        // foreach($transfer_requests as $transfer =>$value){
                        
        //     $dispatch = ManufactureProductTransactions::where('id', $value['request_model_id'])->first()->dispatch()->dispatch_number;            
        //     $transfer_requests[$transfer]['dispatch_number'] = $dispatch;            
            
        // }

        // $adjustment_requests = Approvals::where('request_type', 'Product Adjustment')->where('approved','0')->where('declined','0')->orderBy('created_at','asc')->get()->toArray();
        // foreach($adjustment_requests as $adjustment => $value){
            
        //     $product = ManufactureProducts::where('id', $value['request_model_id'])->first()->description;            
        //     $adjustment_requests[$adjustment]['description'] = $product;            
        // }        
        
        // $transfer_notifications = count($transfer_requests);
        // $adjustment_notifications = count($adjustment_requests);
        // $total_notifications = 0;

        // if(Auth::user()->getSec()->dispatch_transfer_approve)$total_notifications = $total_notifications + count($transfer_requests);
        // if(Auth::user()->getSec()->product_adjustment_approve)$total_notifications = $total_notifications + count($adjustment_requests);      
        
        return view('components.layout'/* , ['total_notifications' => $total_notifications, 'transfer_requests' => $transfer_requests,
        'adjustment_requests' => $adjustment_requests, 'adjustment_notifications' => $adjustment_notifications,
        'transfer_notifications' => $transfer_notifications] */);        
    }
}
