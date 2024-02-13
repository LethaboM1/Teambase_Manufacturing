<?php

namespace App\Http\Livewire\Manufacture\Reports;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureJobcardProductDispatches;

class DispatchLivewire extends Component
{
    
    public $dispatch_report_category,
        $from_date,        
        $to_date,
        $extra_criteria_enabled = false,
        $extra_criteria = 0,
        $job_number_filter = 0,
        $site_number_filter = 0,
        $ref_number_filter = 0,
        $primary_filter,
        $primary_filter_column,
        $primary_filter_text,
        $job_number_filter_old,
        $secondary_filter,
        $secondary_filter_column,
        $secondary_filter_text,
        $site_number_filter_old,
        $tertiary_filter,
        $tertiary_filter_column,
        $tertiary_filter_text,
        $ref_number_filter_old,
        $dispatch_report_jobcard_list = [],
        $dispatch_report_reference_list = [], 
        $dispatch_report_site_list = [];    
    
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

    function resetForm (){
        // dd('resettting the form?');
        $this->dispatch_report_category = 0;
        $this->from_date = '';
        $this->to_date = '';
        $this->extra_criteria_enabled = false;
        $this->extra_criteria = 0;
        $this->job_number_filter = 0;
        $this->site_number_filter = 0;
        $this->ref_number_filter = 0;
        $this->primary_filter = '';
        $this->primary_filter_column = '';
        $this->primary_filter_text = '';
        $this->job_number_filter_old = '';
        $this->secondary_filter = '';
        $this->secondary_filter_column = '';
        $this->secondary_filter_text = '';
        $this->site_number_filter_old = '';
        $this->tertiary_filter = '';
        $this->tertiary_filter_column = '';
        $this->tertiary_filter_text = '';
        $this->ref_number_filter_old = '';
        $this->dispatch_report_jobcard_list = [];
        $this->dispatch_report_reference_list = [];
        $this->dispatch_report_site_list = [];

       /*  $dispatch_report_category_list = SelectLists::dispatch_report_categories;
        array_unshift($dispatch_report_category_list, ['value' => 0, 'name' => 'Select']);

        return view('livewire.manufacture.reports.dispatch-livewire', ['dispatch_report_category_list' => $dispatch_report_category_list]); */
    }

    function updatedJobNumberFilter(){        
        if($this->job_number_filter != '0'){
            //New value is not All
            if($this->job_number_filter_old != '0' && $this->job_number_filter_old != $this->job_number_filter){
                if($this->primary_filter == 'job'){                    
                    $this->primary_filter_text = $this->job_number_filter;
                } elseif ($this->secondary_filter == 'job'){                    
                    $this->secondary_filter_text = $this->job_number_filter;
                } elseif ($this->tertiary_filter == 'job'){                   
                    $this->tertiary_filter_text = $this->job_number_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'job';
                    $this->primary_filter_column = 'job_id';
                    $this->primary_filter_text = $this->job_number_filter;
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'job';
                    $this->secondary_filter_column = 'job_id';
                    $this->secondary_filter_text = $this->job_number_filter;
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'job';                
                    $this->tertiary_filter_column = 'job_id';
                    $this->tertiary_filter_text = $this->job_number_filter;
                }
            }
        } else {
            if($this->primary_filter == 'job'){
                //promote secondary if populated
                if ($this->secondary_filter != ''){
                    $this->primary_filter = $this->secondary_filter;
                    $this->primary_filter_column = $this->secondary_filter_column;
                    $this->primary_filter_text = $this->secondary_filter_text;
                    //promote tertiary if populated
                    if ($this->tertiary_filter != ''){
                        $this->secondary_filter = $this->tertiary_filter;                
                        $this->secondary_filter_column = $this->tertiary_filter_column;
                        $this->secondary_filter_text = $this->tertiary_filter_text; 
                        //clear tertiary filter
                        $this->tertiary_filter = '';                
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';                       
                    } else {
                        $this->secondary_filter = '';                
                        $this->secondary_filter_column = '';
                        $this->secondary_filter_text = '';    
                    }
                } else {
                    $this->primary_filter = '';
                    $this->primary_filter_column = '';
                    $this->primary_filter_text = '';
                }
                                
            } elseif ($this->secondary_filter == 'job'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;
                    //clear tertiary filter
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'job'){
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
            }
        }               
    }

    function updatedSiteNumberFilter(){
        if($this->site_number_filter != '0'){
            //New value is not All
            if($this->site_number_filter_old != '0' && $this->site_number_filter_old != $this->site_number_filter){
                if($this->primary_filter == 'site'){                    
                    $this->primary_filter_text = $this->site_number_filter;
                } elseif ($this->secondary_filter == 'site'){                    
                    $this->secondary_filter_text = $this->site_number_filter;
                } elseif ($this->tertiary_filter == 'site'){                   
                    $this->tertiary_filter_text = $this->site_number_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'site';
                    $this->primary_filter_column = 'site_number';
                    $this->primary_filter_text = $this->site_number_filter;
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'site';
                    $this->secondary_filter_column = 'site_number';
                    $this->secondary_filter_text = $this->site_number_filter;
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'site';                
                    $this->tertiary_filter_column = 'site_number';
                    $this->tertiary_filter_text = $this->site_number_filter;
                }
            }
        } else {
            if($this->primary_filter == 'site'){
                //promote secondary if populated
                if ($this->secondary_filter != ''){
                    $this->primary_filter = $this->secondary_filter;
                    $this->primary_filter_column = $this->secondary_filter_column;
                    $this->primary_filter_text = $this->secondary_filter_text;
                    //promote tertiary if populated
                    if ($this->tertiary_filter != ''){
                        $this->secondary_filter = $this->tertiary_filter;                
                        $this->secondary_filter_column = $this->tertiary_filter_column;
                        $this->secondary_filter_text = $this->tertiary_filter_text;
                        //clear tertiary filter
                        $this->tertiary_filter = '';                
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';
                    } else {
                        $this->secondary_filter = '';                
                        $this->secondary_filter_column = '';
                        $this->secondary_filter_text = '';    
                    } 
                } else {
                    $this->primary_filter = '';
                    $this->primary_filter_column = '';
                    $this->primary_filter_text = '';
                }
                                
            } elseif ($this->secondary_filter == 'site'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;
                    //clear tertiary filter
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';
                }                
            } elseif ($this->tertiary_filter == 'site'){
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
            }
        }            
    }

    function updatedRefNumberFilter(){
        if($this->ref_number_filter != '0'){
            //New value is not All
            if($this->ref_number_filter_old != '0' && $this->ref_number_filter_old != $this->ref_number_filter){
                if($this->primary_filter == 'ref'){                    
                    $this->primary_filter_text = $this->ref_number_filter;
                } elseif ($this->secondary_filter == 'ref'){                    
                    $this->secondary_filter_text = $this->ref_number_filter;
                } elseif ($this->tertiary_filter == 'ref'){                   
                    $this->tertiary_filter_text = $this->ref_number_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'ref';
                    $this->primary_filter_column = 'reference';
                    $this->primary_filter_text = $this->ref_number_filter;
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'ref';
                    $this->secondary_filter_column = 'reference';
                    $this->secondary_filter_text = $this->ref_number_filter;
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'ref';                
                    $this->tertiary_filter_column = 'reference';
                    $this->tertiary_filter_text = $this->ref_number_filter;
                }
            }
        } else {            
            if($this->primary_filter == 'ref'){
                //promote secondary if populated
                if ($this->secondary_filter != ''){
                    $this->primary_filter = $this->secondary_filter;
                    $this->primary_filter_column = $this->secondary_filter_column;
                    $this->primary_filter_text = $this->secondary_filter_text;
                    //promote tertiary if populated
                    if ($this->tertiary_filter != ''){
                        $this->secondary_filter = $this->tertiary_filter;                
                        $this->secondary_filter_column = $this->tertiary_filter_column;
                        $this->secondary_filter_text = $this->tertiary_filter_text;
                        //clear tertiary filter
                        $this->tertiary_filter = '';                
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';
                    } else {
                        $this->secondary_filter = '';                
                        $this->secondary_filter_column = '';
                        $this->secondary_filter_text = '';    
                    }
                } else {
                    $this->primary_filter = '';
                    $this->primary_filter_column = '';
                    $this->primary_filter_text = '';
                }
                                
            } elseif ($this->secondary_filter == 'ref'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;
                    //clear tertiary filter
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';
                }               
            } elseif ($this->tertiary_filter == 'ref'){
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
            }
        }        
    }

    public function buildSelects($dispatch_list){
        // dd($dispatch_list);
        $dispatch_report_jobcard_list = [];
        $dispatch_report_site_list = [];
        $dispatch_report_reference_list = [];
        //Jobs & Sites
        foreach($dispatch_list as $item){            
            if($item->job_id != '0'){array_unshift($dispatch_report_jobcard_list, ['value' => $item->job_id, 'name' => $item->jobcard_number]);}
            if($item->job_id != '0'){array_unshift($dispatch_report_site_list, ['value' => $item->site_number, 'name' => $item->site_number]);}            
        }
        //Remove duplicate Jobs        
        $seenItems = array();
        foreach($dispatch_report_jobcard_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_jobcard_list[$index]);
            else
                $seenItems[] = $item["value"];
        } 
        // array_unshift($dispatch_report_jobcard_list, ['value' => '0', 'name' => 'All']);                       
        //Remove duplicate Sites
        $seenItems = array();
        foreach($dispatch_report_site_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_site_list[$index]);
            else
                $seenItems[] = $item["value"];
        }                
        // array_unshift($dispatch_report_site_list, ['value' => '0', 'name' => 'All']);
        //References
        foreach($dispatch_list as $item){
            array_unshift($dispatch_report_reference_list, ['value' => $item->reference, 'name' => $item->reference]);
        }
        //Remove duplicate Jobs
        $seenItems = array();
        foreach($dispatch_report_reference_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_reference_list[$index]);
            else
                $seenItems[] = $item["value"];
        }
        // array_unshift($dispatch_report_reference_list, ['value' => '0', 'name' => 'All']);
        //return sorted lists
        // dd($dispatch_report_jobcard_list);
        $value  = array_column($dispatch_report_jobcard_list, 'value');
        $name = array_column($dispatch_report_jobcard_list, 'name');
        $this->dispatch_report_jobcard_list = $dispatch_report_jobcard_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_jobcard_list);        
        array_unshift($this->dispatch_report_jobcard_list, ['value' => '0', 'name' => 'All']);
                
        $value  = array_column($dispatch_report_site_list, 'value');
        $name = array_column($dispatch_report_site_list, 'name');
        $this->dispatch_report_site_list = $dispatch_report_site_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_site_list);
        array_unshift($this->dispatch_report_site_list, ['value' => '0', 'name' => 'All']);        
        
        $value  = array_column($dispatch_report_reference_list, 'value');
        $name = array_column($dispatch_report_reference_list, 'name');
        $this->dispatch_report_reference_list = $dispatch_report_reference_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_reference_list);
        array_unshift($this->dispatch_report_reference_list, ['value' => '0', 'name' => 'All']);        
        
    }

    /* public function mount(){
        $this->extra_criteria = 0;
        $this->job_number_filter = 0;
        $this->site_number_filter = 0;
        $this->ref_number_filter = 0;
    } */
    
    public function render()
    {
        $dispatch_report_category_list = SelectLists::dispatch_report_categories;
        array_unshift($dispatch_report_category_list, ['value' => 0, 'name' => 'Select']);        
        
        $this->site_number_filter_old = $this->site_number_filter;
        $this->ref_number_filter_old = $this->ref_number_filter;
        $this->job_number_filter_old = $this->job_number_filter;

        if(isset($this->from_date) && isset($this->to_date) && $this->dispatch_report_category != 0){
            $this->extra_criteria_enabled = true;
        }

        if($this->extra_criteria == true){
            //there is now extra criteria enabled
            
            //Dispatches in Range
            if($this->dispatch_report_category == 'jobcard'){
                //filters - Contractors
                $dispatch_list = ManufactureJobcardProductDispatches::join('manufacture_jobcards', 'manufacture_jobcards.id', '=', 'manufacture_jobcard_product_dispatches.job_id', 'left outer')
                ->select('manufacture_jobcard_product_dispatches.job_id as job_id','manufacture_jobcard_product_dispatches.reference as reference','manufacture_jobcards.jobcard_number as jobcard_number','manufacture_jobcards.site_number as site_number')
                ->where('manufacture_jobcard_product_dispatches.status', 'Dispatched')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '>=', $this->from_date.' 00:00:01')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '<=', $this->to_date.' 23:59:59')
                ->where (function($query){
                    if($this->primary_filter != ''){
                        // dd($this->primary_filter.' '.$this->primary_filter_column.' '.$this->primary_filter_text);
                        $query->where($this->primary_filter_column, 'like', '%'.$this->primary_filter_text.'%')->where
                        (function($query){
                            if($this->secondary_filter != ''){
                                $query->where($this->secondary_filter_column, 'like', '%'.$this->secondary_filter_text.'%')->where
                                (function($query){
                                    if($this->tertiary_filter != ''){
                                        $query->where($this->tertiary_filter_column, 'like', '%'.$this->tertiary_filter_text.'%');
                                    }
                                });
                            }
                        });
                    }
                })
                ->where('manufacture_jobcard_product_dispatches.job_id','!=', '0')                              
                ->get();               
                
            } elseif($this->dispatch_report_category == 'cash'){
                //filters - Cash               
                $dispatch_list = ManufactureJobcardProductDispatches::join('manufacture_jobcards', 'manufacture_jobcards.id', '=', 'manufacture_jobcard_product_dispatches.job_id', 'left outer')
                ->select('manufacture_jobcard_product_dispatches.job_id as job_id','manufacture_jobcard_product_dispatches.customer_id as customer_id','manufacture_jobcard_product_dispatches.reference as reference','manufacture_jobcards.jobcard_number as jobcard_number','manufacture_jobcards.site_number as site_number')
                ->where('manufacture_jobcard_product_dispatches.status', 'Dispatched')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '>=', $this->from_date.' 00:00:01')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '<=', $this->to_date.' 23:59:59')                
                ->where (function($query){
                    if($this->primary_filter != ''){
                        // dd($this->primary_filter.' '.$this->primary_filter_column.' '.$this->primary_filter_text);
                        $query->where($this->primary_filter_column, 'like', '%'.$this->primary_filter_text.'%')->where
                        (function($query){
                            if($this->secondary_filter != ''){
                                $query->where($this->secondary_filter_column, 'like', '%'.$this->secondary_filter_text.'%')->where
                                (function($query){
                                    if($this->tertiary_filter != ''){
                                        $query->where($this->tertiary_filter_column, 'like', '%'.$this->tertiary_filter_text.'%');
                                    }
                                });
                            }
                        });
                    }
                })
                ->where('manufacture_jobcard_product_dispatches.customer_id','!=', '0')                                                    
                ->get();
                
            } else {
                //filters - All - Will add Cash Clients first then add Jobs during loop below - GroupBy Limitiations on dual fields
                $dispatch_list = ManufactureJobcardProductDispatches::join('manufacture_jobcards', 'manufacture_jobcards.id', '=', 'manufacture_jobcard_product_dispatches.job_id', 'left outer')
                ->select('manufacture_jobcard_product_dispatches.job_id as job_id','manufacture_jobcard_product_dispatches.customer_id as customer_id','manufacture_jobcard_product_dispatches.reference as reference','manufacture_jobcards.jobcard_number as jobcard_number','manufacture_jobcards.site_number as site_number')
                ->where('manufacture_jobcard_product_dispatches.status', 'Dispatched')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '>=', $this->from_date.' 00:00:01')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '<=', $this->to_date.' 23:59:59')
                ->where (function($query){
                    if($this->primary_filter != ''){
                        // dd($this->primary_filter.' '.$this->primary_filter_column.' '.$this->primary_filter_text);
                        $query->where($this->primary_filter_column, 'like', '%'.$this->primary_filter_text.'%')->where
                        (function($query){
                            if($this->secondary_filter != ''){
                                $query->where($this->secondary_filter_column, 'like', '%'.$this->secondary_filter_text.'%')->where
                                (function($query){
                                    if($this->tertiary_filter != ''){
                                        $query->where($this->tertiary_filter_column, 'like', '%'.$this->tertiary_filter_text.'%');
                                    }
                                });
                            }
                        });
                    }
                })                                                
                ->get();
                /* $query = str_replace(array('?'), array('\'%s\''), $dispatch_list->toSql());
                $query = vsprintf($query, $dispatch_list->getBindings());
                dd($query); */
                
            } 
            
            $this->buildSelects($dispatch_list);                             
            
            
        }

        

        return view('livewire.manufacture.reports.dispatch-livewire', ['dispatch_report_category_list' => $dispatch_report_category_list]);
    }
}
