<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Approvals;
use App\Models\ManufactureProducts;
use Illuminate\Support\Facades\Auth;
use App\Models\ManufactureProductTransactions;

class NotificationsLivewire extends Component
{
    public $transfer_requests,$adjustment_requests,$transfer_notifications=0,$adjustment_notifications=0,$total_notifications=0;
    
    public $listeners = ['refreshNotifications'];

    function mount(){ 
        $this->transfer_requests = Approvals::where('request_type', 'Dispatch Transfer')->where('approved','0')->where('declined','0')->orderBy('created_at','asc')->get()->toArray();        
        foreach($this->transfer_requests as $transfer =>$value){

            $dispatch = ManufactureProductTransactions::where('id', $value['request_model_id'])->first()->dispatch()->dispatch_number;            
            $this->transfer_requests[$transfer]['dispatch_number'] = $dispatch;            
            
        }

        $this->adjustment_requests = Approvals::where('request_type', 'Stock Adjustment')->where('approved','0')->where('declined','0')->orderBy('created_at','asc')->get()->toArray();
        foreach($this->adjustment_requests as $adjustment => $value){
            
            $product = ManufactureProducts::where('id', $value['request_model_id'])->first()->code . ' - ' . ManufactureProducts::where('id', $value['request_model_id'])->first()->description;                                    
            $this->adjustment_requests[$adjustment]['description'] = $product;                        

        }

    }

    public function refreshNotifications(){
               
        $this->transfer_requests = Approvals::where('request_type', 'Dispatch Transfer')->where('approved','0')->where('declined','0')->orderBy('created_at','asc')->get()->toArray();        
        foreach($this->transfer_requests as $transfer =>$value){
                        
            $dispatch = ManufactureProductTransactions::where('id', $value['request_model_id'])->first()->dispatch()->dispatch_number;            
            $this->transfer_requests[$transfer]['dispatch_number'] = $dispatch;            
            
        }

        $this->adjustment_requests = Approvals::where('request_type', 'Stock Adjustment')->where('approved','0')->where('declined','0')->orderBy('created_at','asc')->get()->toArray();
        foreach($this->adjustment_requests as $adjustment => $value){
            
            $product = ManufactureProducts::where('id', $value['request_model_id'])->first()->code . ' - ' . ManufactureProducts::where('id', $value['request_model_id'])->first()->description;                                    
            $this->adjustment_requests[$adjustment]['description'] = $product;                        

        }

    }
    
    public function render()
    {               
        
        $this->transfer_notifications = count($this->transfer_requests);
        $this->adjustment_notifications = count($this->adjustment_requests);
        $this->total_notifications=0;    
                    
        if(Auth::user()->getSec()->dispatch_transfer_approve_value)$this->total_notifications = $this->total_notifications + count($this->transfer_requests);
        if(Auth::user()->getSec()->product_adjustment_approve_value)$this->total_notifications = $this->total_notifications + count($this->adjustment_requests);              

        return view('livewire..components.notifications-livewire');
    }
}
