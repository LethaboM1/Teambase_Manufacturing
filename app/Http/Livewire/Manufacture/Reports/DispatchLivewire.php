<?php

namespace App\Http\Livewire\Manufacture\Reports;

use Livewire\Component;
use App\Http\Controllers\SelectLists;

class DispatchLivewire extends Component
{
    
    public $dispatch_report_category,
        $from_date,
        $to_date;    

    function updatedFromDate(){
        if($this->to_date < $this->from_date){
            $this->to_date = $this->from_date;
        }
    }        

    function updatedToDate(){
        if($this->from_date > $this->to_date){
            $this->from_date = $this->to_date;
        }
    }
    
    public function render()
    {
        $dispatch_report_category_list = SelectLists::dispatch_report_categories;
        array_unshift($dispatch_report_category_list, ['value' => 0, 'name' => 'Select']);       

        return view('livewire.manufacture.reports.dispatch-livewire', ['dispatch_report_category_list' => $dispatch_report_category_list]);
    }
}
